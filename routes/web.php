<?php

use App\Http\Controllers\Api\V1\Site\HomeController;
use App\Http\Controllers\Api\V1\Seller\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/swagger', 'swagger');
//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);



//================================ Seller =====================================
Route::get('sellers/login', [AuthController::class, 'showLogin'])->name('sellers.login')->middleware('throttle:4,1');
Route::get('sellers/code', [AuthController::class, 'showCode']);


Route::prefix('sellers')
    ->controller(AuthController::class)
    ->name('sellers.')
    ->middleware(['auth:seller'])
    ->group(function () {

        Route::get('password', 'showPassword')->name('show.password');
        Route::get('dashboard', 'showDashboard')->name('show.dashboard');

    });
