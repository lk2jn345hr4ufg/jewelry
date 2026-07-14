<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function show(Business $business)
    {
        abort_unless($business->is_active, 404);

        $business->load(['city', 'category']);

        $reviews = $business->approvedReviews()->latest()->get();

        $alternatives = Business::active()
            ->where('id', '!=', $business->id)
            ->where('city_id', $business->city_id)
            ->where('category_id', $business->category_id)
            ->take(5)
            ->get();

        if ($alternatives->count() < 3) {
            $more = Business::active()
                ->where('id', '!=', $business->id)
                ->where('city_id', $business->city_id)
                ->whereNotIn('id', $alternatives->pluck('id'))
                ->take(5 - $alternatives->count())
                ->get();
            $alternatives = $alternatives->concat($more);
        }

        // Similar stores: same category, but in other cities (complements the
        // "nearby" list above, which is same city + same category).
        $similar = Business::active()
            ->where('id', '!=', $business->id)
            ->where('category_id', $business->category_id)
            ->where('city_id', '!=', $business->city_id)
            ->with(['city', 'category'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        $coupons = $business->coupons()->live()->get();

        return view('business', compact('business', 'reviews', 'alternatives', 'similar', 'coupons'));
    }

    public function storeReview(Request $request, Business $business)
    {
        abort_unless($business->is_active, 404);

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'author_email' => ['nullable', 'email', 'max:150'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $business->reviews()->create($data + ['status' => 'pending']);

        return back()->with('review_status', 'Thanks! Your review was submitted and will appear after moderation.');
    }
}
