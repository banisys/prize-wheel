<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Seller\AuthController;
use App\Http\Controllers\Api\V1\Seller\DiscountCodeController;
use App\Http\Controllers\Api\V1\Seller\WheelController;
use App\Http\Controllers\Api\V1\Seller\SliceController;
use Illuminate\Support\Facades\Route;

class SellerRoutes
{
    public static function init(): void
    {
        Route::prefix('v1/sellers')
            ->controller(AuthController::class)
            ->name('sellers.')
            ->group(function () {
                Route::post('send_verification_code', 'sendVerificationCode');
                Route::post('enter_verification_code', 'enterVerificationCode');
                Route::post('login', 'login');

                Route::post('/password', 'passwordStore')->name('password.store')->middleware(['auth:seller']);
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

        Route::put('v1/slices/{slice}', [SliceController::class, 'update']);
    }
}
