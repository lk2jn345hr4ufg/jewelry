<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'Businesses' => Business::count(),
                'Cities' => City::count(),
                'Categories' => Category::count(),
                'Coupons' => Coupon::count(),
                'Pending reviews' => Review::where('status', 'pending')->count(),
            ],
            'pending' => Review::where('status', 'pending')->with('business')->latest()->take(5)->get(),
            'latest' => Business::with(['city', 'category'])->latest()->take(5)->get(),
        ]);
    }
}
