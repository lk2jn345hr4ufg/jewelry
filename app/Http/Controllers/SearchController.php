<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
        $suggestions = [];

        foreach (Business::active()->with('city')->where('name', 'like', $like)->take(5)->get() as $b) {
            $suggestions[] = [
                'type' => 'Business',
                'label' => $b->name,
                'meta' => $b->city?->full_name,
                'url' => route('business.show', $b),
            ];
        }
        foreach (City::where('name', 'like', $like)->orderByDesc('population')->take(4)->get() as $c) {
            $suggestions[] = [
                'type' => 'City',
                'label' => $c->full_name,
                'meta' => 'Jewelry businesses',
                'url' => route('city.show', $c),
            ];
        }
        foreach (Category::where('name', 'like', $like)->take(4)->get() as $cat) {
            $suggestions[] = [
                'type' => 'Category',
                'label' => $cat->name,
                'meta' => 'Browse category',
                'url' => route('search', ['q' => $cat->name]),
            ];
        }

        return response()->json(['suggestions' => $suggestions]);
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';

        $businesses = Business::active()
            ->with(['city', 'category'])
            ->when($q !== '', function ($query) use ($like) {
                $query->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                        ->orWhere('about', 'like', $like)
                        ->orWhereHas('city', fn ($c) => $c->where('name', 'like', $like))
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like));
                });
            })
            ->paginate(12)
            ->withQueryString();

        return view('search', compact('businesses', 'q'));
    }
}
