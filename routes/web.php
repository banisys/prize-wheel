<?php

use App\Http\Controllers\Api\V1\Site\HomeController;
use App\Http\Controllers\Api\V1\Seller\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/swagger', 'swagger');

//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);

//================================ Seller =====================================
Route::get('sellers/login', [AuthController::class, 'loginShow'])->name('sellers.login');
Route::get('sellers/code', [AuthController::class, 'codeShow']);
Route::get('sellers/password', [AuthController::class, 'passwordShow']);
Route::get('sellers/password-forgot', [AuthController::class, 'passwordForgotShow']);


Route::prefix('sellers')
    ->controller(AuthController::class)
    ->name('sellers.')
    ->middleware(['auth:seller'])
    ->group(function () {

        Route::get('password-register', 'passwordRegisterShow')->name('show.password.register');
        Route::get('dashboard', 'showDashboard')->name('show.dashboard');

    });
