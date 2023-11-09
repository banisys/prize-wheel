<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\ResponseFactory;

class HomeController extends Controller
{
    public $inertia;

    public function __construct()
    {
        $this->inertia = new ResponseFactory();
        $this->inertia->setRootView('site');
    }

    public function index()
    {
        return $this->inertia->render('Home');
    }
}
