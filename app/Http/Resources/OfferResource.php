<?php

namespace App\Http\Resources;

use App\Models\Coupon;
use App\Support\StoreUrl;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * OfferResponseData from the API contract.
 *
 * Coupons entered by hand in the admin panel are exposed too, otherwise the
 * client would not see them while de-duplicating and would send them again.
 * Their missing API-only fields fall back to sensible values.
 *
 * @mixin Coupon
 */
class OfferResource extends JsonResource
{
    public function toArray($request): array
    {
        $business = $this->business;
        $meta = $this->meta ?? [];

        return [
            'shop_name' => $business?->name,
            'shop_url' => StoreUrl::canonical($business?->website ?: $business?->website_host),
            'slug' => $this->slug ?: 'coupon-'.$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'coupon_type' => $this->resolvedType(),
            'starts_at' => $this->starts_at?->toDateString(),
            'valid_at' => $this->expires_at?->toDateString(),
            'discount_value' => $this->discount,
            'coupon_code' => $this->code,
            'deep_link' => $this->resolvedDeepLink(),
            'countries' => $meta['countries'] ?? [],
            'categories' => $meta['categories'] ?? [],
            'sources' => $meta['sources'] ?? [],
        ];
    }
}
