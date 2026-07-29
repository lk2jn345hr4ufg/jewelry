<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Static pages
        $urls[] = ['loc' => route('home'), 'priority' => '1.0', 'freq' => 'daily'];
        $urls[] = ['loc' => route('coupons.index'), 'priority' => '0.7', 'freq' => 'daily'];

        // Categories (only those with at least one active business)
        $categories = Category::whereHas('businesses', fn ($q) => $q->where('is_active', true))->get();
        foreach ($categories as $category) {
            $urls[] = ['loc' => route('category.show', $category), 'priority' => '0.7', 'freq' => 'weekly'];
        }

        // Cities that have active businesses
        $cities = City::whereHas('businesses', fn ($q) => $q->where('is_active', true))->get();
        foreach ($cities as $city) {
            $urls[] = ['loc' => route('city.show', $city), 'priority' => '0.6', 'freq' => 'weekly'];
        }

        // Active business profiles — the core of the sitemap
        Business::where('is_active', true)
            ->select('slug', 'updated_at')
            ->orderBy('id')
            ->chunk(1000, function ($businesses) use (&$urls) {
                foreach ($businesses as $business) {
                    $urls[] = [
                        'loc' => route('business.show', $business),
                        'priority' => '0.8',
                        'freq' => 'weekly',
                        'lastmod' => optional($business->updated_at)->toAtomString(),
                    ];
                }
            });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
