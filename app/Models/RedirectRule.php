<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RedirectRule extends Model
{
    protected $table = 'redirects';

    protected $fillable = ['from_pattern', 'to_pattern', 'is_regex', 'status_code', 'is_active', 'hits'];

    protected function casts(): array
    {
        return [
            'is_regex' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('redirect_rules');
        static::saved($flush);
        static::deleted($flush);
    }

    /** Active rules, cached — exact matches first, then regex rules. */
    public static function cached()
    {
        return Cache::remember('redirect_rules', 3600, fn () => static::where('is_active', true)
            ->orderBy('is_regex')
            ->orderBy('id')
            ->get(['id', 'from_pattern', 'to_pattern', 'is_regex', 'status_code']));
    }
}
