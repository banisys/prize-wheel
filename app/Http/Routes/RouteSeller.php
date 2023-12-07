<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Seller\AuthController;
use Illuminate\Support\Facades\Route;

class RouteSeller
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
    }
}
