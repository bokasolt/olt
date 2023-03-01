<?php

use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\PurchaseController;
use App\Http\Controllers\Frontend\User\ProfileController;
use Tabuna\Breadcrumbs\Trail;

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the user has not confirmed their email
 */
Route::group(['as' => 'user.', 'middleware' => ['auth', 'password.expires', config('boilerplate.access.middleware.verified')]], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('is_user')
        ->name('dashboard')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.index')
                ->push(__('Dashboard'), route('frontend.user.dashboard'));
        });

    Route::get('shopping-cart', [DashboardController::class, 'shoppingCard'])
        ->middleware('is_user')
        ->name('shopping-cart')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.user.dashboard')
                ->push(__('User\'s shopping cart'), route('frontend.user.shopping-cart'));
        });

    Route::get('purchase', [PurchaseController::class, 'index'])
        ->middleware('is_user')
        ->name('purchase')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.user.dashboard')
                ->push(__('Purchase'), route('frontend.user.purchase'));
        });

    Route::get('account', [AccountController::class, 'index'])
        ->name('account')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.user.dashboard')
                ->push(__('My Account'), route('frontend.user.account'));
        });

    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
