<?php

use App\Http\Controllers\Api\OfferController;
use Illuminate\Support\Facades\Route;

/*
| Vouchers API — token-authenticated offer import and read-back.
| Rate limit is inline: this app declares no named "api" limiter.
*/
Route::middleware(['api.token', 'throttle:60,1'])->group(function () {
    Route::post('/submit_offers', [OfferController::class, 'submit']);
    Route::get('/get_offers', [OfferController::class, 'index']);
});
