<?php

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Seller\WheelController as SellerWheelController;
use App\Http\Controllers\Site\WheelController as SiteWheelController;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\TokenController;
use Illuminate\Support\Facades\Route;

Route::view('/swagger', 'swagger');

//================================ Seller =====================================
Route::get('sellers/dashboard', [DashboardController::class, 'index'])->middleware('auth:seller')->name('sellers.show.dashboard');

Route::prefix('sellers')
    ->controller(AuthController::class)
    ->group(function () {

        Route::get('login', 'loginShow')->name('sellers.login');
        Route::get('code', 'codeShow');
        Route::get('password', 'passwordShow');
        Route::get('password-forgot', 'passwordForgotShow');

        Route::middleware('auth:seller')
            ->group(function () {

                Route::get('password-register', 'passwordRegisterShow');
            });
    });


Route::resource('sellers/wheels', SellerWheelController::class)->only(['index', 'edit', 'show'])->middleware('auth:seller');
Route::get('sellers/slices/{slice}/edit', [SellerWheelController::class, 'editSlice']);

Route::get('sellers/tokens/{wheel}', [TokenController::class, 'index'])->middleware('auth:seller');

Route::get('sellers/orders/plan', [OrderController::class, 'plan'])->middleware('auth:seller');
Route::get('sellers/orders/sms', [OrderController::class, 'sms'])->middleware('auth:seller');


//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);
Route::get('{wheel}', [SiteWheelController::class, 'show']);
