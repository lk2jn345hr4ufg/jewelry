<?php

namespace App\Http\Requests\Api;

use App\Support\StoreUrl;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

/**
 * POST /submit_offers — the body is a bare list of offers.
 *
 * Everything the contract calls "aliases and normalization" happens in
 * prepareForValidation(), so the rules and the importer both see one shape.
 */
class SubmitOffersRequest extends FormRequest
{
    /** Access is already settled by the api.token middleware. */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*' => ['array'],

            '*.shop_name' => ['required', 'string', 'max:191'],
            '*.shop_url' => ['required', 'string', 'max:500', $this->resolvableHost()],
            '*.store_logo_url' => ['nullable', 'string', 'url', 'max:500'],

            '*.slug' => ['required', 'string', 'max:191', 'distinct'],
            '*.title' => ['required', 'string', 'max:150'],
            '*.description' => ['nullable', 'string', 'max:5000'],
            '*.coupon_type' => ['required', 'string', 'in:code,deal'],
            '*.starts_at' => ['nullable', 'date_format:Y-m-d'],
            '*.valid_at' => ['nullable', 'date_format:Y-m-d'],
            '*.discount_value' => ['nullable', 'string', 'max:60'],
            '*.coupon_code' => ['nullable', 'string', 'max:60'],
            '*.deep_link' => ['nullable', 'string', 'url', 'max:500'],

            '*.country_code' => ['nullable', 'array'],
            '*.country_code.*' => ['string', 'max:8'],
            '*.category_name' => ['nullable', 'array'],
            '*.category_name.*' => ['string', 'max:191'],
            '*.sources' => ['nullable', 'array'],
            '*.sources.*' => ['string', 'max:191'],
        ];
    }

    /** Checks the whole body: it must be a list, non-empty and within the batch cap. */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $offers = $this->all();

                if (! is_array($offers) || $offers === [] || ! array_is_list($offers)) {
                    $validator->errors()->add('offers', 'The request body must be a non-empty list of offers.');

                    return;
                }

                $max = (int) config('vouchers.max_batch');
                if (count($offers) > $max) {
                    $validator->errors()->add('offers', "A batch may contain at most {$max} offers.");
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $offers = $this->all();

        if (! is_array($offers)) {
            return;
        }

        $this->replace(array_map(
            fn ($offer) => is_array($offer) ? $this->normalize($offer) : $offer,
            $offers
        ));
    }

    /** Applies the contract's aliases and value normalisation to one offer. */
    protected function normalize(array $offer): array
    {
        foreach (['shop_name', 'shop_url', 'store_logo_url', 'slug', 'title', 'coupon_code', 'discount_value', 'deep_link'] as $field) {
            if (isset($offer[$field]) && is_string($offer[$field])) {
                $offer[$field] = trim($offer[$field]) ?: null;
            }
        }

        if (isset($offer['coupon_type']) && is_string($offer['coupon_type'])) {
            $offer['coupon_type'] = mb_strtolower(trim($offer['coupon_type']));
        }

        // A bare "example.com/deal" is a link the feed meant as absolute; adding
        // the scheme keeps the batch alive instead of failing the url rule.
        foreach (['store_logo_url', 'deep_link'] as $field) {
            if (filled($offer[$field] ?? null) && is_string($offer[$field])) {
                $offer[$field] = $this->toUrl($offer[$field]);
            }
        }

        // "countries" and "categories" are accepted as aliases.
        $countries = $offer['country_code'] ?? $offer['countries'] ?? null;
        $categories = $offer['category_name'] ?? $offer['categories'] ?? null;
        unset($offer['countries'], $offer['categories']);

        if ($countries !== null) {
            $offer['country_code'] = $this->toList($countries, fn ($v) => mb_strtoupper($v));
        }

        if ($categories !== null) {
            $offer['category_name'] = $this->toList($categories);
        }

        if (isset($offer['sources'])) {
            $offer['sources'] = $this->toList($offer['sources']);
        }

        foreach (['starts_at', 'valid_at'] as $field) {
            if (array_key_exists($field, $offer)) {
                $offer[$field] = $this->toDate($offer[$field]);
            }
        }

        return $offer;
    }

    /**
     * Turns an array or a comma-separated string into a clean list:
     * split, trimmed, empties dropped, deduplicated case-insensitively.
     */
    protected function toList(mixed $value, ?callable $map = null): array
    {
        $items = is_array($value) ? $value : [$value];
        $out = [];
        $seen = [];

        foreach ($items as $item) {
            if (! is_scalar($item)) {
                continue;
            }

            foreach (explode(',', (string) $item) as $part) {
                $part = trim($part);
                if ($part === '') {
                    continue;
                }

                $part = $map ? $map($part) : $part;
                $key = mb_strtolower($part);

                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $out[] = $part;
                }
            }
        }

        return $out;
    }

    /** Prepends https:// when a link arrives without a scheme. */
    protected function toUrl(string $value): string
    {
        return preg_match('~^[a-z][a-z0-9+.-]*://~i', $value) ? $value : 'https://'.$value;
    }

    /** Any parseable date or timestamp collapses to yyyy-mm-dd; junk is left for the validator. */
    protected function toDate(mixed $value): mixed
    {
        if (blank($value)) {
            return null;
        }

        // Only values large enough to be a real epoch are read as timestamps —
        // "20261231" is a squashed date, not a moment in 1970.
        if (is_numeric($value) && (float) $value >= 1_000_000_000) {
            $seconds = (float) $value >= 1_000_000_000_000 ? (int) ((float) $value / 1000) : (int) $value;

            return Carbon::createFromTimestamp($seconds)->toDateString();
        }

        if (is_numeric($value)) {
            $value = (string) $value; // "20261231" still deserves a parse attempt
        }

        if (! is_string($value)) {
            return $value;
        }

        try {
            return Carbon::parse(trim($value))->toDateString();
        } catch (\Throwable) {
            return $value;
        }
    }

    /** shop_url has to yield a host, otherwise no store can be matched or created. */
    protected function resolvableHost(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            if (! StoreUrl::host(is_string($value) ? $value : null)) {
                $fail('The :attribute field must contain a valid store URL.');
            }
        };
    }

    /**
     * The validated batch.
     *
     * all() rather than validated(): prepareForValidation() has already
     * normalised every field, the body passed validation, and the importer
     * reads named keys only, so unknown extras from the client are inert.
     *
     * @return array<int, array<string, mixed>>
     */
    public function offers(): array
    {
        return $this->all();
    }
}
