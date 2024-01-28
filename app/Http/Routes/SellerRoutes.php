<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Seller\AuthController;
use App\Http\Controllers\Api\V1\Seller\DiscountCodeController;
use App\Http\Controllers\Api\V1\Seller\WheelController;
use Illuminate\Support\Facades\Route;

class SellerRoutes
{
    public static function init(): void
    {
        Route::post('v1/sellers/send_verification_code', [AuthController::class, 'sendVerificationCode']);
        Route::post('v1/sellers/enter_verification_code', [AuthController::class, 'enterVerificationCode']);
        Route::post('v1/sellers/login', [AuthController::class, 'login']);


        Route::prefix('v1/sellers')
            ->controller(AuthController::class)
            ->name('sellers.')
            ->middleware(['auth:seller'])
            ->group(function () {

                Route::post('/password', 'passwordStore')->name('password.store');
            });


        Route::prefix('v1/wheels')
            ->controller(WheelController::class)
            ->name('v1.wheels.')
            ->middleware(['auth:seller'])
            ->group(function () {

                Route::post('/', 'store')->name('store');
                Route::put('/{wheel}', 'update')->name('update');
                Route::delete('/{wheel}', 'destroy')->name('destroy');

                Route::get('/{wheel}/search', 'search')->name('search');
            });



        Route::prefix('v1/discount_codes')
            ->controller(DiscountCodeController::class)
            ->name('v1.discount_codes.')
            ->middleware(['auth:seller'])
            ->group(function () {

                Route::post('/', 'store')->name('store');
                Route::get('/{slice_id}', 'fetch')->name('fetch');
                Route::delete('/{slice_id}', 'destroy')->name('destroy');

            });
    }
}
