<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Seller\AuthController;
use App\Http\Controllers\Api\V1\Seller\DiscountCodeController;
use App\Http\Controllers\Api\V1\Seller\OrderController;
use App\Http\Controllers\Api\V1\Seller\WheelController;
use App\Http\Controllers\Api\V1\Seller\SliceController;
use App\Http\Controllers\Api\V1\Seller\TokenController;
use Illuminate\Support\Facades\Route;

class SellerRoutes
{
    public static function init(): void
    {
        Route::prefix('v1/sellers')
            ->controller(AuthController::class)
            ->group(function () {
                Route::post('send_verification_code', 'sendVerificationCode');
                Route::post('check_verification_code', 'checkVerificationCode');
                Route::post('login', 'login');

                Route::post('/password', 'passwordStore')->name('password.store')->middleware(['auth:seller']);
            });


        Route::prefix('v1/sellers/wheels')
            ->controller(WheelController::class)
            ->name('v1.wheels.')
            ->middleware(['auth:seller'])
            ->group(function () {

                Route::post('/', 'store')->name('store');
                Route::put('/{wheel}', 'update')->name('update');
                Route::delete('/{wheel}', 'destroy')->name('destroy');

                Route::get('/{wheel}/search', 'search')->name('search');
            });



        Route::prefix('v1/sellers/discount_codes')
            ->controller(DiscountCodeController::class)
            ->name('v1.discount_codes.')
            ->middleware(['auth:seller'])
            ->group(function () {

                Route::post('/', 'store')->name('store');
                Route::get('/{slice_id}', 'fetch')->name('fetch');
                Route::delete('/{slice_id}', 'destroy')->name('destroy');
            });

        Route::put('v1/sellers/slices/{slice}', [SliceController::class, 'update']);


        Route::prefix('v1/sellers/tokens')
            ->controller(TokenController::class)
            ->name('v1.tokens.')
            ->middleware(['auth:seller'])
            ->group(function () {

                Route::post('{wheel}', 'store')->name('store');
                Route::delete('not-used/{wheel}', 'destroyNotUsed')->name('destroy.not.used');
                Route::get('download-excel/{wheel}', 'downloadExcel')->name('download.excel');
                Route::get('search/{wheel}', 'search')->name('search');
            });


        Route::post('v1/sellers/orders/plan', [OrderController::class, 'storePlan']);
        Route::post('v1/sellers/orders/sms', [OrderController::class, 'storeSms']);
    }
}
