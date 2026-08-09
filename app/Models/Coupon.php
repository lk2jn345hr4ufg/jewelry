<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'business_id', 'slug', 'title', 'coupon_type', 'code', 'description',
        'discount', 'starts_at', 'expires_at', 'deep_link', 'meta', 'is_active', 'origin',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Offers that may be shown right now: active, already started and not expired.
     * expires_at is inclusive — an offer ending today is still live today.
     */
    public function scopeLive($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', $today));
    }

    /** Falls back for coupons created in the admin panel, which carry no type. */
    public function resolvedType(): string
    {
        return $this->coupon_type ?: (filled($this->code) ? 'code' : 'deal');
    }

    /** Where the offer sends the visitor; the store's own site if nothing better. */
    public function resolvedDeepLink(): ?string
    {
        return $this->deep_link ?: $this->business?->website;
    }
}
