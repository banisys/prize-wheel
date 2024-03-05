<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('layout-inertia.seller');

        return Inertia::render('Dashboard');
    }
}
