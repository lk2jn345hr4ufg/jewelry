<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    protected $fillable = ['name', 'slug', 'state', 'lat', 'lng', 'population'];

    protected static function booted(): void
    {
        static::saving(function (City $city) {
            if (empty($city->slug)) {
                $city->slug = Str::slug($city->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function activeBusinesses()
    {
        return $this->hasMany(Business::class)->where('is_active', true);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->state ? "{$this->name}, {$this->state}" : $this->name;
    }
}
