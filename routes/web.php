<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index']);
Route::get('/p_15', [HomeController::class, 'p_15']);
Route::get('/p_12', [HomeController::class, 'p_12']);
Route::get('/p_10', [HomeController::class, 'p_10']);
