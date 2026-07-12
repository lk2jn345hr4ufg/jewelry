<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public const CITIES_PER_PAGE = 8;

    public function index()
    {
        $cities = City::withCount('activeBusinesses')
            ->orderByDesc('active_businesses_count')
            ->orderBy('name')
            ->take(self::CITIES_PER_PAGE)
            ->get();

        $totalCities = City::count();

        $categories = Category::withCount(['businesses' => fn ($q) => $q->where('is_active', true)])
            ->orderByDesc('businesses_count')
            ->get();

        $recent = Business::active()
            ->with(['city', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $discountStores = Business::active()
            ->whereHas('liveCoupons')
            ->with(['city', 'category'])
            ->withCount([
                'liveCoupons',
                'liveCoupons as codes_count' => fn ($q) => $q->whereNotNull('code')->where('code', '!=', ''),
                'liveCoupons as deals_count' => fn ($q) => $q->where(fn ($w) => $w->whereNull('code')->orWhere('code', '')),
            ])
            ->orderByDesc('live_coupons_count')
            ->orderBy('name')
            ->take(8)
            ->get();

        $latestReviews = Review::approved()
            ->with('business.city')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('cities', 'totalCities', 'categories', 'recent', 'latestReviews', 'discountStores'));
    }

    public function loadCities(Request $request)
    {
        $offset = max(0, (int) $request->query('offset', 0));

        $cities = City::withCount('activeBusinesses')
            ->orderByDesc('active_businesses_count')
            ->orderBy('name')
            ->skip($offset)
            ->take(self::CITIES_PER_PAGE)
            ->get();

        return response()->json([
            'cities' => $cities->map(fn ($c) => [
                'name' => $c->full_name,
                'url' => route('city.show', $c),
                'count' => $c->active_businesses_count,
            ]),
            'hasMore' => City::count() > $offset + self::CITIES_PER_PAGE,
        ]);
    }
}
