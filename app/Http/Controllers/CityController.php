<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public const PER_PAGE = 9;

    public function show(City $city)
    {
        return $this->renderListing($city, null);
    }

    public function category(City $city, Category $category)
    {
        return $this->renderListing($city, $category);
    }

    protected function renderListing(City $city, ?Category $category)
    {
        $query = Business::active()
            ->with(['category', 'city'])
            ->where('city_id', $city->id)
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->orderBy('name');

        $total = (clone $query)->count();
        $businesses = $query->take(self::PER_PAGE)->get();

        // All active businesses for the map (capped for payload size)
        $mapBusinesses = Business::active()
            ->where('city_id', $city->id)
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->whereNotNull('lat')->whereNotNull('lng')
            ->take(200)
            ->get()
            ->map(fn ($b) => [
                'name' => $b->name,
                'lat' => $b->lat,
                'lng' => $b->lng,
                'address' => $b->address,
                'url' => route('business.show', $b),
            ]);

        $biggestCities = City::whereHas('activeBusinesses')
            ->withCount('activeBusinesses')
            ->orderByDesc('active_businesses_count')
            ->orderBy('name')
            ->take(10)
            ->get();

        // Categories connected to this city (admin-managed), fallback to categories with businesses here
        $cityCategories = $city->categories()->orderBy('name')->get();
        if ($cityCategories->isEmpty()) {
            $cityCategories = Category::whereHas('businesses', fn ($q) => $q->where('city_id', $city->id)->where('is_active', true))
                ->orderBy('name')->get();
        }

        return view('city', [
            'city' => $city,
            'category' => $category,
            'businesses' => $businesses,
            'total' => $total,
            'mapBusinesses' => $mapBusinesses,
            'biggestCities' => $biggestCities,
            'cityCategories' => $cityCategories,
            'hasMore' => $total > self::PER_PAGE,
        ]);
    }

    public function loadBusinesses(Request $request, City $city)
    {
        $offset = max(0, (int) $request->query('offset', 0));
        $categorySlug = $request->query('category');
        $category = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;

        $query = Business::active()
            ->with(['category', 'city'])
            ->where('city_id', $city->id)
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->orderBy('name');

        $total = (clone $query)->count();
        $businesses = $query->skip($offset)->take(self::PER_PAGE)->get();

        $html = '';
        foreach ($businesses as $business) {
            $html .= view('partials.business-card', compact('business'))->render();
        }

        return response()->json([
            'html' => $html,
            'hasMore' => $total > $offset + self::PER_PAGE,
        ]);
    }
}
