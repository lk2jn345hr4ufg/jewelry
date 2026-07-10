<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
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

        $stores = Business::active()
            ->whereHas('liveCoupons')
            ->with(['city'])
            ->withCount('liveCoupons')
            ->orderByDesc('live_coupons_count')
            ->take(8)
            ->get();

        return view('coupons', compact('coupons', 'stores'));
    }

    public function show(Business $business)
    {
        abort_unless($business->is_active, 404);

        $business->load(['city', 'category']);

        // All live offers: entries with a promo code first, soonest expiry first.
        $offers = $business->liveCoupons()
            ->orderByRaw("(code IS NULL OR code = '') asc")
            ->orderByRaw('expires_at IS NULL asc')
            ->orderBy('expires_at')
            ->get();

        $codes = $offers->filter(fn ($c) => filled($c->code))->values();
        $deals = $offers->filter(fn ($c) => blank($c->code))->values();

        $highlights = [
            'total' => $offers->count(),
            'codes' => $codes->count(),
            'deals' => $deals->count(),
            'best' => $offers->pluck('discount')->filter()->first(),
            'nextExpiry' => $offers->pluck('expires_at')->filter()->sort()->first(),
        ];

        // Categories: the store's own category plus other categories present in its city.
        $cityCategories = Category::whereHas('businesses', fn ($q) => $q
                ->where('is_active', true)->where('city_id', $business->city_id))
            ->withCount(['businesses' => fn ($q) => $q
                ->where('is_active', true)->where('city_id', $business->city_id)])
            ->orderBy('name')
            ->get();

        // Alternative stores that also have live offers (same city first).
        $alternatives = Business::active()
            ->where('id', '!=', $business->id)
            ->whereHas('liveCoupons')
            ->with(['city', 'category'])
            ->withCount([
                'liveCoupons',
                'liveCoupons as codes_count' => fn ($q) => $q->whereNotNull('code')->where('code', '!=', ''),
                'liveCoupons as deals_count' => fn ($q) => $q->where(fn ($w) => $w->whereNull('code')->orWhere('code', '')),
            ])
            ->orderByRaw('city_id = ? desc', [$business->city_id])
            ->orderByDesc('live_coupons_count')
            ->take(4)
            ->get();

        $reviews = $business->approvedReviews()->latest()->take(6)->get();

        $faq = $this->buildFaq($business, $highlights);

        return view('coupons-store', compact(
            'business', 'offers', 'codes', 'deals', 'highlights',
            'cityCategories', 'alternatives', 'reviews', 'faq'
        ));
    }

    /**
     * Generates the FAQ from live data so answers never go stale.
     */
    protected function buildFaq(Business $business, array $h): array
    {
        $name = $business->name;
        $city = $business->city->full_name;

        $faq = [
            [
                'q' => "How many {$name} coupons and deals are available right now?",
                'a' => $h['total'] > 0
                    ? "There " . ($h['total'] === 1 ? 'is currently 1 verified offer' : "are currently {$h['total']} verified offers")
                        . " for {$name}: {$h['codes']} " . str('promo code')->plural($h['codes'])
                        . " and {$h['deals']} " . str('deal')->plural($h['deals'])
                        . " that require no code."
                    : "There are no active offers for {$name} at the moment — check back soon, new coupons are added regularly.",
            ],
            [
                'q' => "How do I use a {$name} coupon code?",
                'a' => "Click the code to copy it, then mention it in store or enter it at checkout on the {$name} website. Deals marked “no code needed” are applied automatically or simply by mentioning the offer.",
            ],
            [
                'q' => "Are these {$name} offers verified?",
                'a' => "Every offer listed here is submitted by the business and checked against its expiry date — expired coupons are removed from this page automatically.",
            ],
            [
                'q' => "Where is {$name} located?",
                'a' => trim(($business->address ? "{$name} is located at {$business->address}, " : "{$name} is located in ") . $city . ".")
                    . " See the store profile for the map, phone numbers and opening hours.",
            ],
        ];

        if ($h['best']) {
            $faq[] = [
                'q' => "What is the best {$name} discount right now?",
                'a' => "The strongest current offer is {$h['best']}."
                    . ($h['nextExpiry'] ? " The next offer to expire ends on " . $h['nextExpiry']->format('F j, Y') . ", so don't wait too long." : ''),
            ];
        }

        return $faq;
    }
}
