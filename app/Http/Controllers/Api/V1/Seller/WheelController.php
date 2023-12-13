<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;

class WheelController extends Controller
{
    public function create(): InertiaResponse
    {
        Inertia::setRootView('seller');

        return Inertia::render('wheels/Create');
    }
}
