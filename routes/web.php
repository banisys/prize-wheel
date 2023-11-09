<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Seller\AuthController;
use Illuminate\Support\Facades\Route;


//================================ Site =====================================
Route::get('/', [HomeController::class, 'index']);



//================================ Seller =====================================
Route::get('/login', [AuthController::class, 'show'])->name('seller.login');
