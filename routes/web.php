<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// ---------- Public ----------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/site-unlock', function (\Illuminate\Http\Request $request) {
    $request->validate(['password' => ['required', 'string']]);

    if (hash_equals((string) config('app.site_password'), $request->input('password'))) {
        $request->session()->put('site_unlocked', true);

        return redirect('/');
    }

    return back()->withErrors(['password' => 'Wrong password, try again.']);
})->name('site.unlock');
Route::get('/cities/load', [HomeController::class, 'loadCities'])->name('cities.load');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/deals', [CouponController::class, 'index'])->name('coupons.index');
Route::get('/deals/category/{category}', [CouponController::class, 'category'])->name('coupons.category');
Route::get('/deals/{business}', [CouponController::class, 'show'])->name('coupons.show');

Route::get('/business/{business}', [BusinessController::class, 'show'])->name('business.show');
Route::post('/business/{business}/reviews', [BusinessController::class, 'storeReview'])->name('business.review');

Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/category/{category}/businesses', [CategoryController::class, 'loadBusinesses'])->name('category.businesses');

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

        Route::get('site-check', [Admin\SiteCheckController::class, 'index'])->name('sitecheck.index');
        Route::post('site-check/run', [Admin\SiteCheckController::class, 'run'])->name('sitecheck.run');
        Route::post('site-check/reset', [Admin\SiteCheckController::class, 'reset'])->name('sitecheck.reset');
        Route::post('site-check/hide-dead', [Admin\SiteCheckController::class, 'hideDead'])->name('sitecheck.hidedead');
        Route::patch('site-check/{business}/hide', [Admin\SiteCheckController::class, 'hide'])->name('sitecheck.hide');

        Route::resource('redirects', Admin\RedirectController::class)->except(['show'])
            ->parameters(['redirects' => 'redirect']);
        Route::post('redirects-bulk', [Admin\RedirectController::class, 'bulk'])->name('redirects.bulk');

        Route::get('ai-rewrite', [Admin\AiContentController::class, 'index'])->name('ai.rewrite');
        Route::post('ai-rewrite/run', [Admin\AiContentController::class, 'run'])->name('ai.rewrite.run');

        Route::get('import', [Admin\ImportController::class, 'form'])->name('import.form');
        Route::post('import/preview', [Admin\ImportController::class, 'preview'])->name('import.preview');
        Route::post('import/run', [Admin\ImportController::class, 'run'])->name('import.run');

        Route::get('reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/status', [Admin\ReviewController::class, 'updateStatus'])->name('reviews.status');
        Route::delete('reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
});
