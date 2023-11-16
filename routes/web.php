<?php

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Seller\AuthController;
use Illuminate\Support\Facades\Route;


//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);



//================================ Seller =====================================
Route::get('sellers/login', [AuthController::class, 'show'])->name('seller.login');
