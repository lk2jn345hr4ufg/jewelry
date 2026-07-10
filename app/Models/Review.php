<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['business_id', 'author_name', 'author_email', 'rating', 'body', 'status'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
