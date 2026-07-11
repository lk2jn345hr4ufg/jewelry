<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    /** Importable business fields (value => label shown in the mapping UI). */
    public const TARGETS = [
        'ignore' => '— ignore this column —',
        'name' => 'Business name',
        'profile_slug' => 'Profile URL (derive name & city from the link slug)',
        'email' => 'Email',
        'website' => 'Website',
        'phone' => 'Phone',
        'phone_alt' => 'Alternative phone',
        'address' => 'Address',
        'about' => 'About / description',
        'city' => 'City name',
        'state' => 'State / region',
        'category' => 'Category name',
        'lat' => 'Latitude',
        'lng' => 'Longitude',
    ];

    public function form()
    {
        return view('admin.imports.form');
    }

    /** Step 2: store the upload, detect the delimiter, show column mapping. */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:51200'],
        ]);

        $token = Str::random(32);
        $request->file('file')->storeAs('imports', $token.'.csv');

        [$delimiter, $headers, $samples] = $this->inspect(Storage::path('imports/'.$token.'.csv'));

        if (empty($headers)) {
            Storage::delete('imports/'.$token.'.csv');

            return back()->withErrors(['file' => 'Could not read any rows from this file.']);
        }

        return view('admin.imports.map', [
            'token' => $token,
            'delimiter' => $delimiter,
            'headers' => $headers,
            'samples' => $samples,
            'guessed' => array_map(fn ($h) => $this->guessTarget($h), $headers),
            'targets' => self::TARGETS,
            'categories' => Category::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
        ]);
    }

    /** Step 3: run the import and report results. */
    public function run(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'alpha_num', 'size:32'],
            'delimiter' => ['required', 'in:comma,semicolon,tab'],
            'mapping' => ['required', 'array'],
            'mapping.*' => ['in:'.implode(',', array_keys(self::TARGETS))],
            'default_category_id' => ['required', 'exists:categories,id'],
            'default_city_id' => ['nullable', 'exists:cities,id'],
            'import_hidden' => ['nullable', 'boolean'],
        ]);

        $path = Storage::path('imports/'.$data['token'].'.csv');
        abort_unless(is_file($path), 404, 'Uploaded file expired — please upload again.');

        set_time_limit(0);

        $delimiter = ['comma' => ',', 'semicolon' => ';', 'tab' => "\t"][$data['delimiter']];
        $mapping = array_values($data['mapping']);
        $defaultCity = $data['default_city_id'] ? City::find($data['default_city_id']) : null;
        $isActive = ! $request->boolean('import_hidden');

        // In-memory caches so 20k rows don't mean 60k queries.
        $citiesBySlug = City::pluck('id', 'slug')->all();
        $categoriesByKey = Category::get()->keyBy(fn ($c) => mb_strtolower($c->name))->map->id->all();
        $existing = Business::pluck('id', 'slug')->all();

        $report = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];
        $row = 0;

        $fh = fopen($path, 'r');
        $first = true;

        while (($cols = fgetcsv($fh, 0, $delimiter)) !== false) {
            if ($first) {
                $first = false;
                continue; // header row
            }
            $row++;

            try {
                $values = [];
                foreach ($mapping as $i => $target) {
                    if ($target === 'ignore') {
                        continue;
                    }
                    $raw = trim($this->stripBom((string) ($cols[$i] ?? '')));
                    if ($raw !== '') {
                        $values[$target] = $raw;
                    }
                }

                // Derive name (and maybe city) from a profile URL slug.
                $derivedCityId = null;
                if (isset($values['profile_slug'])) {
                    [$derivedName, $derivedCityId] = $this->fromProfileUrl($values['profile_slug'], $citiesBySlug);
                    $values['name'] ??= $derivedName;
                }

                if (blank($values['name'] ?? null)) {
                    $report['skipped']++;
                    continue;
                }

                // Resolve city: explicit column > derived from slug > default.
                $cityId = null;
                if (isset($values['city'])) {
                    $citySlug = Str::slug($values['city']);
                    $cityId = $citiesBySlug[$citySlug] ?? null;
                    if (! $cityId) {
                        $city = City::create(['name' => $values['city'], 'state' => $values['state'] ?? null]);
                        $cityId = $citiesBySlug[$city->slug] = $city->id;
                    }
                }
                $cityId = $cityId ?? $derivedCityId ?? $defaultCity?->id;

                if (! $cityId) {
                    $report['skipped']++;
                    continue;
                }

                // Resolve category: explicit column > default.
                $categoryId = null;
                if (isset($values['category'])) {
                    $key = mb_strtolower($values['category']);
                    $categoryId = $categoriesByKey[$key] ?? null;
                    if (! $categoryId) {
                        $category = Category::create(['name' => $values['category']]);
                        $categoryId = $categoriesByKey[$key] = $category->id;
                    }
                }
                $categoryId = $categoryId ?? (int) $data['default_category_id'];

                $attributes = [
                    'category_id' => $categoryId,
                    'city_id' => $cityId,
                ];
                foreach (['about', 'address', 'phone', 'phone_alt'] as $f) {
                    if (isset($values[$f])) {
                        $attributes[$f] = $values[$f];
                    }
                }
                if (isset($values['email']) && filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
                    $attributes['email'] = $values['email'];
                }
                if (isset($values['website'])) {
                    $attributes['website'] = preg_match('~^https?://~i', $values['website'])
                        ? $values['website']
                        : 'https://'.$values['website'];
                }
                foreach (['lat', 'lng'] as $f) {
                    if (isset($values[$f]) && is_numeric(str_replace(',', '.', $values[$f]))) {
                        $attributes[$f] = (float) str_replace(',', '.', $values[$f]);
                    }
                }

                // Dedupe on the slug the Business model would generate.
                $slug = Str::slug($values['name']);
                if (isset($existing[$slug])) {
                    Business::whereKey($existing[$slug])->update($attributes);
                    $report['updated']++;
                } else {
                    $business = Business::create($attributes + [
                        'name' => $values['name'],
                        'is_active' => $isActive,
                    ]);
                    $existing[$business->slug] = $business->id;
                    $report['created']++;
                }

                // Keep the category ↔ city connection in sync.
                static $pivotSeen = [];
                if (! isset($pivotSeen[$categoryId.'-'.$cityId])) {
                    Category::find($categoryId)?->cities()->syncWithoutDetaching([$cityId]);
                    $pivotSeen[$categoryId.'-'.$cityId] = true;
                }
            } catch (\Throwable $e) {
                $report['skipped']++;
                if (count($report['errors']) < 20) {
                    $report['errors'][] = 'Row '.($row + 1).': '.$e->getMessage();
                }
            }
        }

        fclose($fh);
        Storage::delete('imports/'.$data['token'].'.csv');

        return redirect()->route('admin.import.form')->with('import_report', $report);
    }

    /** Reads delimiter, headers and up to 5 sample rows. */
    protected function inspect(string $path): array
    {
        $firstLine = $this->stripBom((string) fgets(fopen($path, 'r')));
        $delimiter = 'comma';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = 'semicolon';
        }
        if (substr_count($firstLine, "\t") > substr_count($firstLine, $delimiter === 'comma' ? ',' : ';')) {
            $delimiter = 'tab';
        }
        $char = ['comma' => ',', 'semicolon' => ';', 'tab' => "\t"][$delimiter];

        $fh = fopen($path, 'r');
        $headers = fgetcsv($fh, 0, $char) ?: [];
        $headers = array_map(fn ($h) => trim($this->stripBom((string) $h)), $headers);

        $samples = [];
        while (count($samples) < 5 && ($rowData = fgetcsv($fh, 0, $char)) !== false) {
            $samples[] = $rowData;
        }
        fclose($fh);

        return [$delimiter, $headers, $samples];
    }

    /** Suggests a mapping target from a header name. */
    protected function guessTarget(string $header): string
    {
        $h = mb_strtolower($header);

        return match (true) {
            str_contains($h, 'mail') => 'email',
            str_contains($h, 'website') || str_contains($h, 'site') => 'website',
            $h === 'url' || str_contains($h, 'profile') || str_contains($h, 'link') => 'profile_slug',
            str_contains($h, 'name') || str_contains($h, 'title') => 'name',
            str_contains($h, 'phone') || str_contains($h, 'tel') => 'phone',
            str_contains($h, 'address') || str_contains($h, 'street') => 'address',
            str_contains($h, 'city') || str_contains($h, 'town') => 'city',
            str_contains($h, 'state') || str_contains($h, 'region') => 'state',
            str_contains($h, 'categor') => 'category',
            str_contains($h, 'about') || str_contains($h, 'desc') => 'about',
            $h === 'lat' || str_contains($h, 'latitude') => 'lat',
            $h === 'lng' || $h === 'lon' || str_contains($h, 'longitude') => 'lng',
            default => 'ignore',
        };
    }

    /**
     * "…/profile/the-diamond-ring-company-san-jose/" →
     * name "The Diamond Ring Company", city id of "san-jose" when the
     * slug's tail matches a known city (longest match wins).
     */
    protected function fromProfileUrl(string $url, array $citiesBySlug): array
    {
        $slug = Str::of($url)->rtrim('/')->afterLast('/')->value();
        $tokens = array_values(array_filter(explode('-', $slug)));

        if (empty($tokens)) {
            return [null, null];
        }

        for ($take = min(4, count($tokens) - 1); $take >= 1; $take--) {
            $candidate = implode('-', array_slice($tokens, -$take));
            if (isset($citiesBySlug[$candidate])) {
                $nameTokens = array_slice($tokens, 0, count($tokens) - $take);

                return [Str::title(implode(' ', $nameTokens)), $citiesBySlug[$candidate]];
            }
        }

        return [Str::title(implode(' ', $tokens)), null];
    }

    protected function stripBom(string $value): string
    {
        return ltrim($value, "\xEF\xBB\xBF");
    }
}
