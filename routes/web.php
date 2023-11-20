<?php

use App\Http\Controllers\Api\V1\Site\HomeController;
use App\Http\Controllers\Api\V1\Seller\AuthController;
use Illuminate\Support\Facades\Route;


//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);



//================================ Seller =====================================
Route::get('sellers/login', [AuthController::class, 'show'])->name('seller.login');
