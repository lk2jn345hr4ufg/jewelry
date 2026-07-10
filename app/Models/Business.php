<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'city_id', 'about', 'address',
        'phone', 'phone_alt', 'website', 'email', 'lat', 'lng', 'hours', 'is_active',
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
