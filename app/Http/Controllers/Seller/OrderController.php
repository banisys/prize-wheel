<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('layout-inertia.seller');

        $plans = Plan::get();

        return Inertia::render('orders/Index', [
            'plans' => $plans
        ]);
    }
}
