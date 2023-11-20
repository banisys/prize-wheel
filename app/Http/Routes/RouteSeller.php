<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Seller\AuthController;
use Illuminate\Support\Facades\Route;

class RouteSeller
{
    public static function init(): void
    {
        Route::prefix('v1/sellers')->controller(AuthController::class)->name('sellers.')->group(function () {

            Route::post('/send_verification_code', 'sendVerificationCode')->name('sendVerificationCode');
        });
    }
}
