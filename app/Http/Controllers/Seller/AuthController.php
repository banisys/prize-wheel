<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function loginShow(): InertiaResponse | RedirectResponse
    {
        if (auth('seller')->check())
            return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('seller');

        return Inertia::render('Login');
    }

    public function codeShow(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('seller');

        return Inertia::render('Code');
    }

    public function passwordRegisterShow(): InertiaResponse | RedirectResponse
    {
        // if (isset(auth('seller')->user()->password) && auth('seller')->user()->password)
        //     return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('seller');

        $seller = auth('seller')->user()->only(['id', 'mobile']);

        return Inertia::render('PasswordRegister', [
            'seller' => $seller
        ]);
    }

    public function showDashboard(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('seller');

        return Inertia::render('Dashboard');
    }

    public function passwordShow(): InertiaResponse | RedirectResponse
    {
        if (isset(auth('seller')->user()->password) && auth('seller')->user()->password)
            return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('seller');

        return Inertia::render('Password');
    }

    public function passwordForgotShow(): InertiaResponse | RedirectResponse
    {
        if (auth('seller')->check())
            return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('seller');

        return Inertia::render('PasswordForgot');
    }
}
