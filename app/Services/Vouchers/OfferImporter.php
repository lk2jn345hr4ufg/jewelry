<?php

namespace App\Services\Vouchers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Coupon;
use App\Support\HtmlSanitizer;
use App\Support\StoreUrl;
use Illuminate\Support\Facades\DB;

/**
 * Writes a batch of API offers into businesses + coupons.
 *
 * Offers are keyed by their slug, so re-sending a batch updates rather than
 * duplicates. The whole batch runs in one transaction: rows that cannot be
 * placed are skipped and reported, while a genuine failure rolls everything
 * back and the client can safely retry.
 */
class OfferImporter
{
    /** @var array<string, Business> stores already resolved in this batch, by host */
    protected array $storesByHost = [];

    /** @var array<string, int> category id by lower-cased name */
    protected array $categories = [];

    /** @var array<string, Coupon> existing coupons in this batch, by slug */
    protected array $coupons = [];

    protected array $report = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'stores_created' => 0,
        'notes' => [],
    ];

    /**
     * @param  array<int, array<string, mixed>>  $offers
     * @return array<string, mixed> import report for the log
     */
    public function import(array $offers): array
    {
        $this->preload($offers);

        DB::transaction(function () use ($offers) {
            foreach ($offers as $index => $offer) {
                $this->importOne((int) $index, $offer);
            }
        });

        return $this->report;
    }

    /** One query per lookup table instead of three per offer. */
    protected function preload(array $offers): void
    {
        $hosts = [];
        $slugs = [];

        foreach ($offers as $offer) {
            if ($host = StoreUrl::host($offer['shop_url'] ?? null)) {
                $hosts[$host] = true;
            }
            if (! empty($offer['slug'])) {
                $slugs[] = $offer['slug'];
            }
        }

        // Active branches first, then oldest: a chain shares one domain across
        // many locations, and the coupon must always land on the same one.
        Business::whereIn('website_host', array_keys($hosts))
            ->orderByDesc('is_active')
            ->orderBy('id')
            ->get()
            ->each(function (Business $business) {
                $this->storesByHost[$business->website_host] ??= $business;
            });

        $this->categories = Category::pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim((string) $name)) => $id])
            ->all();

        $this->coupons = Coupon::whereIn('slug', $slugs)->get()->keyBy('slug')->all();
    }

    protected function importOne(int $index, array $offer): void
    {
        $host = StoreUrl::host($offer['shop_url'] ?? null);
        $store = $host ? $this->resolveStore($host, $offer) : null;

        if (! $store) {
            $this->report['skipped']++;

            return;
        }

        $slug = $offer['slug'];
        $existing = $this->coupons[$slug] ?? null;

        $type = $offer['coupon_type'];
        $code = $offer['coupon_code'] ?? null;

        // A "code" offer with no code would render as "no code needed"; store it
        // as the deal it actually is instead of publishing a broken coupon.
        if ($type === 'code' && blank($code)) {
            $type = 'deal';
            $this->note($index, 'coupon_type "code" without coupon_code, stored as "deal"');
        }

        $attributes = [
            'business_id' => $store->id,
            'title' => $offer['title'],
            'coupon_type' => $type,
            'code' => $type === 'code' ? $code : null,
            'description' => HtmlSanitizer::clean($offer['description'] ?? null),
            'discount' => $offer['discount_value'] ?? null,
            'starts_at' => $offer['starts_at'] ?? null,
            // valid_at is the end of the offer window; the site stores it as expires_at.
            'expires_at' => $offer['valid_at'] ?? null,
            'deep_link' => ($offer['deep_link'] ?? null) ?: $store->website,
            'meta' => $this->mergeMeta($existing?->meta, $offer),
            'is_active' => (bool) config('vouchers.coupon_active_on_import'),
            'origin' => 'api',
        ];

        if ($existing) {
            $existing->fill($attributes)->save();
            $this->report['updated']++;

            return;
        }

        $this->coupons[$slug] = Coupon::create($attributes + ['slug' => $slug]);
        $this->report['created']++;
    }

    /** Finds the store for this host, creating it when the feed brings a new one. */
    protected function resolveStore(string $host, array $offer): ?Business
    {
        if ($store = $this->storesByHost[$host] ?? null) {
            $this->enrich($store, $offer);

            return $store;
        }

        if (! config('vouchers.autocreate_stores')) {
            $this->note(null, "unknown store {$host}, auto-creation is disabled");

            return null;
        }

        $categoryId = $this->categoryIdFor($offer);

        if (! $categoryId) {
            $this->note(null, "unknown store {$host}, no category matched and no default is configured");

            return null;
        }

        $store = Business::create([
            'name' => $offer['shop_name'],
            'website' => StoreUrl::canonical($offer['shop_url']),
            'logo_url' => $offer['store_logo_url'] ?? null,
            'category_id' => $categoryId,
            'city_id' => null, // the feed carries no location; an admin fills it in
            'is_active' => (bool) config('vouchers.autocreate_store_active'),
            'origin' => 'api',
        ]);

        $this->report['stores_created']++;

        return $this->storesByHost[$host] = $store;
    }

    /**
     * Fills gaps on an existing store without touching curated fields: a hand
     * written name, category or address describes this branch better than a feed.
     */
    protected function enrich(Business $store, array $offer): void
    {
        if (blank($store->logo_url) && filled($offer['store_logo_url'] ?? null)) {
            $store->logo_url = $offer['store_logo_url'];
        }

        if (blank($store->website)) {
            $store->website = StoreUrl::canonical($offer['shop_url']);
        }

        if ($store->isDirty()) {
            $store->save();
        }
    }

    /** First feed category matching a site category, otherwise the configured default. */
    protected function categoryIdFor(array $offer): ?int
    {
        foreach ($offer['category_name'] ?? [] as $name) {
            if ($id = $this->categories[mb_strtolower(trim((string) $name))] ?? null) {
                return (int) $id;
            }
        }

        $default = config('vouchers.default_category_id');

        return $default && Category::whereKey($default)->exists() ? (int) $default : null;
    }

    /**
     * Lists in meta accumulate instead of being replaced, mirroring the contract's
     * "added to existing relations without detaching". First spelling seen wins.
     *
     * @param  array<string, mixed>|null  $old
     */
    protected function mergeMeta(?array $old, array $offer): array
    {
        $incoming = [
            'countries' => $offer['country_code'] ?? [],
            'categories' => $offer['category_name'] ?? [],
            'sources' => $offer['sources'] ?? [],
        ];

        $merged = [];

        foreach ($incoming as $key => $values) {
            $merged[$key] = collect($old[$key] ?? [])
                ->merge($values)
                ->filter(fn ($v) => is_scalar($v) && trim((string) $v) !== '')
                ->map(fn ($v) => trim((string) $v))
                ->unique(fn ($v) => mb_strtolower($v))
                ->values()
                ->all();
        }

        return $merged;
    }

    /** Keeps at most 50 notes so a bad feed cannot blow up the log line. */
    protected function note(?int $index, string $message): void
    {
        if (count($this->report['notes']) >= 50) {
            return;
        }

        $this->report['notes'][] = $index === null ? $message : "offer #{$index}: {$message}";
    }
}
