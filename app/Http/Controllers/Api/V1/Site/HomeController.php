<?php

namespace App\Http\Controllers\Api\V1\Site;

use App\Http\Controllers\Controller;
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
        // return view('test');
        return $this->inertia->render('Home');
    }
}
