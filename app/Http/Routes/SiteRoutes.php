<?php

namespace App\Http\Routes;

use App\Http\Controllers\Api\V1\Site\WheelController;
use Illuminate\Support\Facades\Route;

class SiteRoutes
{
    public static function init(): void
    {
        Route::post('v1/users/loign', [WheelController::class, 'loign']);
        Route::post('v1/users/enter_verification_code', [WheelController::class, 'enterVerificationCode']);
        Route::post('v1/users/user_requirement', [WheelController::class, 'userRequirementStore'])->middleware(['auth']);
        Route::get('v1/users/wheel_data/{wheel}', [WheelController::class, 'wheelDataFetch'])->middleware(['auth']);


        Route::post('v1/prizes', [WheelController::class, 'prizeStore'])->middleware(['auth']);




    }
}
