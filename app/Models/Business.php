<?php

namespace App\Models;

use App\Support\StoreUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'city_id', 'about', 'address',
        'phone', 'phone_alt', 'website', 'logo_url', 'website_host', 'email',
        'lat', 'lng', 'hours', 'is_active', 'origin',
    ];

    protected $casts = [
        'hours' => 'array',
        'is_active' => 'boolean',
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (Business $business) {
            if (empty($business->slug)) {
                $slug = Str::slug($business->name);
                $i = 1;
                $base = $slug;
                while (static::where('slug', $slug)->where('id', '!=', $business->id ?? 0)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $business->slug = $slug;
            }

            // Keep the matching host in step with the website, whoever edits it.
            if ($business->isDirty('website')) {
                $business->website_host = StoreUrl::host($business->website);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    /** Delegates to Coupon::scopeLive so the "is it live?" rule lives in one place. */
    public function liveCoupons()
    {
        return $this->hasMany(Coupon::class)->live();
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function averageRating(): ?float
    {
        $avg = $this->approvedReviews()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }
}
