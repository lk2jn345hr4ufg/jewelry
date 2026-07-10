<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// ---------- Public ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cities/load', [HomeController::class, 'loadCities'])->name('cities.load');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/deals', [CouponController::class, 'index'])->name('coupons.index');

Route::get('/business/{business}', [BusinessController::class, 'show'])->name('business.show');
Route::post('/business/{business}/reviews', [BusinessController::class, 'storeReview'])->name('business.review');

Route::get('/jewelry/{city}', [CityController::class, 'show'])->name('city.show');
Route::get('/jewelry/{city}/businesses', [CityController::class, 'loadBusinesses'])->name('city.businesses');
Route::get('/jewelry/{city}/{category}', [CityController::class, 'category'])->name('city.category');

// ---------- Admin ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', Admin\CategoryController::class)->except(['show']);
        Route::resource('cities', Admin\CityController::class)->except(['show']);
        Route::resource('businesses', Admin\BusinessController::class)->except(['show']);
        Route::resource('coupons', Admin\CouponController::class)->except(['show']);

        Route::get('reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/status', [Admin\ReviewController::class, 'updateStatus'])->name('reviews.status');
        Route::delete('reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
});
