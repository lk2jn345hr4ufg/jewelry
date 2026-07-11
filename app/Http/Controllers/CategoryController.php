<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public const PER_PAGE = 9;

    public function show(Category $category)
    {
        $query = Business::active()
            ->with(['category', 'city'])
            ->where('category_id', $category->id)
            ->orderBy('name');

        $total = (clone $query)->count();
        $businesses = $query->take(self::PER_PAGE)->get();

        // Markers for the map (capped for payload size)
        $mapBusinesses = Business::active()
            ->where('category_id', $category->id)
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

        // Cities offering this category, ranked by how many businesses they have in it.
        $cities = City::whereHas('businesses', fn ($q) => $q
                ->where('is_active', true)->where('category_id', $category->id))
            ->withCount(['businesses' => fn ($q) => $q
                ->where('is_active', true)->where('category_id', $category->id)])
            ->orderByDesc('businesses_count')
            ->orderBy('name')
            ->take(30)
            ->get();

        $otherCategories = Category::where('id', '!=', $category->id)
            ->withCount(['businesses' => fn ($q) => $q->where('is_active', true)])
            ->orderByDesc('businesses_count')
            ->get();

        return view('category', [
            'category' => $category,
            'businesses' => $businesses,
            'total' => $total,
            'mapBusinesses' => $mapBusinesses,
            'cities' => $cities,
            'otherCategories' => $otherCategories,
            'hasMore' => $total > self::PER_PAGE,
        ]);
    }

    public function loadBusinesses(Request $request, Category $category)
    {
        $offset = max(0, (int) $request->query('offset', 0));

        $query = Business::active()
            ->with(['category', 'city'])
            ->where('category_id', $category->id)
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
