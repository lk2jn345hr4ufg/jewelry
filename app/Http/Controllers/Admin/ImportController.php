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
    protected const DAYS = [
        'monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed',
        'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun',
    ];

    /** Importable fields (value => label shown in the mapping UI). */
    public static function targets(): array
    {
        $targets = [
            'ignore' => '— ignore this column —',
            'name' => 'Business name',
            'profile_slug' => 'Profile URL (derive name & city from the link slug)',
            'email' => 'Email',
            'website' => 'Website / domain',
            'phone' => 'Phone',
            'phone_alt' => 'Alternative phone',
            'address' => 'Address',
            'zip' => 'ZIP / postal code (appended to the address)',
            'about' => 'About / description',
            'city' => 'City name',
            'state' => 'State / region',
            'category' => 'Category name',
            'lat' => 'Latitude',
            'lng' => 'Longitude',
        ];

        foreach (self::DAYS as $key => $short) {
            $day = ucfirst($key);
            $targets["hours_{$key}_open"] = "Opening hours — {$day}: opens at";
            $targets["hours_{$key}_close"] = "Opening hours — {$day}: closes at";
        }

        return $targets;
    }

    public function form()
    {
        return view('admin.imports.form');
    }

    /** Step 2: store the upload, detect the delimiter, show column mapping. */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:102400'],
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
            'targets' => self::targets(),
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
            'mapping.*' => ['in:'.implode(',', array_keys(self::targets()))],
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

        // In-memory caches so thousands of rows don't mean thousands of queries.
        $citiesBySlug = City::pluck('id', 'slug')->all();
        $categoriesByKey = Category::get()->keyBy(fn ($c) => mb_strtolower($c->name))->map->id->all();

        // Dedupe on name *within a city*, so chain stores in different cities stay separate.
        $existing = [];
        foreach (Business::get(['id', 'name', 'city_id']) as $b) {
            $existing[Str::slug($b->name).'|'.$b->city_id] = $b->id;
        }

        $report = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];
        $row = 0;
        $pivotSeen = [];

        $fh = fopen($path, 'r');
        $first = true;

        while (($cols = fgetcsv($fh, 0, $delimiter)) !== false) {
            if ($first) {
                $first = false;
                continue; // header row
            }
            $row++;

            try {
                // Collect mapped values; the first non-empty column wins per field.
                $values = [];
                foreach ($mapping as $i => $target) {
                    if ($target === 'ignore') {
                        continue;
                    }
                    $raw = trim($this->clean((string) ($cols[$i] ?? '')));
                    if ($raw !== '') {
                        $values[$target] ??= $raw;
                    }
                }

                // Derive name (and maybe city) from a profile URL slug.
                $derivedCityId = null;
                if (isset($values['profile_slug'])) {
                    [$derivedName, $derivedCityId] = $this->fromProfileUrl($values['profile_slug'], $citiesBySlug);
                    $values['name'] ??= $derivedName;
                }

                // "Pandora Outlet – Oxon Hill" + city "Oxon Hill" → "Pandora Outlet"
                if (isset($values['name'], $values['city'])) {
                    $values['name'] = $this->stripCitySuffix($values['name'], $values['city']);
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
                    if (! $cityId && $citySlug !== '') {
                        $city = City::create([
                            'name' => $values['city'],
                            'state' => $values['state'] ?? null,
                            'lat' => is_numeric($values['lat'] ?? null) ? (float) $values['lat'] : null,
                            'lng' => is_numeric($values['lng'] ?? null) ? (float) $values['lng'] : null,
                        ]);
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
                if (isset($values['zip'])) {
                    $attributes['address'] = trim(($attributes['address'] ?? '').' '.$values['zip']);
                }
                if (isset($values['email']) && filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
                    $attributes['email'] = $values['email'];
                }
                if (isset($values['website'])) {
                    $attributes['website'] = $this->normalizeWebsite($values['website']);
                }
                foreach (['lat', 'lng'] as $f) {
                    if (isset($values[$f]) && is_numeric(str_replace(',', '.', $values[$f]))) {
                        $attributes[$f] = (float) str_replace(',', '.', $values[$f]);
                    }
                }
                if ($hours = $this->assembleHours($values)) {
                    $attributes['hours'] = $hours;
                }

                $dedupeKey = Str::slug($values['name']).'|'.$cityId;
                if (isset($existing[$dedupeKey])) {
                    Business::whereKey($existing[$dedupeKey])->update($attributes);
                    $report['updated']++;
                } else {
                    $business = Business::create($attributes + [
                        'name' => $values['name'],
                        'is_active' => $isActive,
                    ]);
                    $existing[$dedupeKey] = $business->id;
                    $report['created']++;
                }

                // Keep the category ↔ city connection in sync.
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
        $firstLine = $this->clean((string) fgets(fopen($path, 'r')));
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
        $headers = array_map(fn ($h) => trim($this->clean((string) $h)), $headers);

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
        $h = mb_strtolower(trim($header));

        // Per-day opening hours: "opening_monday_open", "monday_close", "sat_open"…
        foreach (self::DAYS as $day => $short) {
            $s = mb_strtolower($short);
            if (str_contains($h, $day) || preg_match('/(^|_)'.$s.'(_|$)/', $h)) {
                if (str_contains($h, 'close')) {
                    return "hours_{$day}_close";
                }
                if (str_contains($h, 'open')) {
                    return "hours_{$day}_open";
                }
            }
        }

        return match (true) {
            str_contains($h, 'mail') => 'email',
            str_contains($h, 'maps') => 'ignore',
            str_contains($h, 'profile') || str_contains($h, 'post_url') => 'profile_slug',
            str_contains($h, 'website') || str_contains($h, 'domain') || $h === 'site' || str_contains($h, 'salon_url') => 'website',
            $h === 'url' || str_contains($h, 'link') => 'profile_slug',
            str_contains($h, 'name') || str_contains($h, 'title') => 'name',
            str_contains($h, 'phone') || str_contains($h, 'tel') => 'phone',
            str_contains($h, 'address') || str_contains($h, 'street') => 'address',
            str_contains($h, 'zip') || str_contains($h, 'postal') => 'zip',
            str_contains($h, 'city') || str_contains($h, 'town') => 'city',
            str_contains($h, 'state') || str_contains($h, 'region') => 'state',
            str_contains($h, 'categor') => 'category',
            str_contains($h, 'about') || str_contains($h, 'desc') => 'about',
            $h === 'lat' || str_contains($h, 'latitude') => 'lat',
            $h === 'lng' || $h === 'lon' || str_contains($h, 'longitude') => 'lng',
            default => 'ignore',
        };
    }

    /** Combines hours_{day}_open / _close values into the model's hours array. */
    protected function assembleHours(array $values): array
    {
        $hours = [];
        foreach (self::DAYS as $day => $short) {
            $open = $values["hours_{$day}_open"] ?? null;
            $close = $values["hours_{$day}_close"] ?? null;
            if ($open && $close) {
                $hours[$short] = "{$open} – {$close}";
            } elseif ($open) {
                $hours[$short] = $open;
            }
        }

        return $hours;
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

    /** Removes a trailing "– City" / "- City" / ", City" from a business name. */
    protected function stripCitySuffix(string $name, string $city): string
    {
        $stripped = preg_replace(
            '/\s*[\-\x{2013}\x{2014}|,]\s*'.preg_quote($city, '/').'\s*$/iu',
            '',
            $name
        );

        return trim((string) $stripped) !== '' ? trim($stripped) : $name;
    }

    /** Prepends https:// when missing and strips utm_* tracking parameters. */
    protected function normalizeWebsite(string $url): string
    {
        if (! preg_match('~^https?://~i', $url)) {
            $url = 'https://'.$url;
        }

        $parts = parse_url($url);
        if (! empty($parts['host']) && ! empty($parts['query'])) {
            parse_str($parts['query'], $params);
            $params = array_filter($params, fn ($k) => ! str_starts_with(mb_strtolower((string) $k), 'utm_'), ARRAY_FILTER_USE_KEY);
            $url = ($parts['scheme'] ?? 'https').'://'.$parts['host'].($parts['path'] ?? '');
            if ($params) {
                $url .= '?'.http_build_query($params);
            }
        }

        return Str::limit($url, 250, '');
    }

    /** Strips the UTF-8 BOM and decodes HTML entities like &#8211;. */
    protected function clean(string $value): string
    {
        $value = ltrim($value, "\xEF\xBB\xBF");

        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
