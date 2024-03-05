<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function loginShow(): InertiaResponse | RedirectResponse
    {
        if (auth('seller')->check())
            return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('layout-inertia.seller');

        return Inertia::render('auth/Login');
    }

    public function codeShow(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('layout-inertia.seller');

        return Inertia::render('auth/Code');
    }

    public function passwordRegisterShow(): InertiaResponse | RedirectResponse
    {
        // if (isset(auth('seller')->user()->password) && auth('seller')->user()->password)
        //     return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('layout-inertia.seller');

        $seller = auth('seller')->user()->only(['id', 'mobile']);

        return Inertia::render('auth/PasswordRegister', [
            'seller' => $seller
        ]);
    }

    public function passwordShow(): InertiaResponse | RedirectResponse
    {
        if (isset(auth('seller')->user()->password) && auth('seller')->user()->password)
            return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('layout-inertia.seller');

        return Inertia::render('auth/Password');
    }

    public function passwordForgotShow(): InertiaResponse | RedirectResponse
    {
        if (auth('seller')->check())
            return redirect()->route('sellers.show.dashboard');

        Inertia::setRootView('layout-inertia.seller');

        return Inertia::render('auth/PasswordForgot');
    }
}
