<?php

use App\Http\Controllers\Api\V1\Site\HomeController;
use App\Http\Controllers\Api\V1\Seller\AuthController;
use Illuminate\Support\Facades\Route;


//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);



//================================ Seller =====================================
Route::get('sellers/login', [AuthController::class, 'showLogin'])->name('sellers.login');
Route::get('sellers/code', [AuthController::class, 'showCode']);
Route::get('sellers/password', [AuthController::class, 'showPassword'])->middleware(['auth:seller']);


Route::view('/swagger', 'swagger');
