<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home');
    }

    public function p_15()
    {
        return Inertia::render('p_15');
    }

    public function p_12()
    {
        return Inertia::render('p_12');
    }

    public function p_10()
    {
        return Inertia::render('p_10');
    }
}
