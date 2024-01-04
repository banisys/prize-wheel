<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Site\WheelController;
use Illuminate\Support\Facades\Route;

class SiteRoutes
{
    public static function init(): void
    {
        Route::post('v1/users/step_one_loign', [WheelController::class, 'stepOneLoign']);

        Route::post('v1/users/enter_verification_code', [WheelController::class, 'enterVerificationCode']);


    }
}
