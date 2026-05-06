<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BusinessController::class, 'home'])
    ->name('home');

Route::get('/search', [BusinessController::class, 'search'])
    ->name('businesses.search');

Route::get('/businesses/{business}', [BusinessController::class, 'show'])
    ->name('businesses.show');

Route::middleware('auth')->group(function () {
    Route::post('/businesses/{business}/favorite', [BusinessController::class, 'toggleFavorite'])
        ->name('businesses.favorite');

    Route::post('/businesses/{business}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');

    Route::post('/reviews/{review}/respond', [ReviewController::class, 'respond'])
        ->name('reviews.respond');

    Route::resource('service-requests', ServiceRequestController::class);

    Route::post('/service-requests/{serviceRequest}/quotes', [QuoteController::class, 'store'])
        ->name('quotes.store');

    Route::post('/quotes/{quote}/accept', [QuoteController::class, 'accept'])
        ->name('quotes.accept');

    Route::resource('messages', MessageController::class)
        ->only(['index', 'show', 'store']);
});

Route::middleware(['auth', 'provider'])->group(function () {
    Route::get('/dashboard/provider', [BusinessController::class, 'providerDashboard'])
        ->name('dashboard.provider');

    Route::get('/business/create', [BusinessController::class, 'create'])
        ->name('businesses.create');

    Route::post('/business', [BusinessController::class, 'store'])
        ->name('businesses.store');

    Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])
        ->name('businesses.edit');

    Route::put('/businesses/{business}', [BusinessController::class, 'update'])
        ->name('businesses.update');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/businesses', [AdminController::class, 'businesses'])
            ->name('businesses');

        Route::patch('/businesses/{business}/approve', [AdminController::class, 'approveBusiness'])
            ->name('businesses.approve');

        Route::patch('/businesses/{business}/suspend', [AdminController::class, 'suspendBusiness'])
            ->name('businesses.suspend');

        Route::patch('/businesses/{business}/featured', [AdminController::class, 'toggleFeatured'])
            ->name('businesses.featured');

        Route::get('/reviews', [AdminController::class, 'reviews'])
            ->name('reviews');

        Route::patch('/reviews/{review}/approve', [AdminController::class, 'approveReview'])
            ->name('reviews.approve');

        Route::patch('/reviews/{review}/reject', [AdminController::class, 'rejectReview'])
            ->name('reviews.reject');
    });
