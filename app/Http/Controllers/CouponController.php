<?php

namespace App\Http\Controllers;

use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::live()
            ->with('business.city', 'business.category')
            ->whereHas('business', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->paginate(12);

        return view('coupons', compact('coupons'));
    }
}
