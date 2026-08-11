<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubmitOffersRequest;
use App\Http\Resources\OfferResource;
use App\Models\Coupon;
use App\Services\Vouchers\OfferImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    /** POST /api/submit_offers */
    public function submit(SubmitOffersRequest $request, OfferImporter $importer): JsonResponse
    {
        $report = $importer->import($request->offers());

        // The contract fixes the response to {"status":"ok"}, so counts and the
        // reasons rows were skipped only survive in the log.
        Log::info('vouchers: submit_offers', $report);

        return response()->json(['status' => 'ok']);
    }

    /**
     * GET /api/get_offers
     *
     * Used by the client to de-duplicate, so it must mirror exactly what the
     * site shows: live coupons belonging to visible stores.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min($request->integer('per_page', 100), (int) config('vouchers.max_per_page')));
        $page = max(1, $request->integer('page', 1));

        $offers = Coupon::live()
            ->whereHas('business', fn ($q) => $q->where('is_active', true))
            ->with('business')
            ->orderBy('id') // stable order, so paging cannot skip or repeat rows
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'offers' => OfferResource::collection($offers->getCollection())->resolve(),
            'pagination' => [
                'total_pages' => $offers->lastPage(),
                'page' => $offers->currentPage(),
            ],
        ]);
    }
}
