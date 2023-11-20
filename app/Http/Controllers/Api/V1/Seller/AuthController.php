<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use App\Http\Controllers\Api\V1\Helper;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    public $inertia;

    public function __construct()
    {
        $this->inertia = new ResponseFactory();
        $this->inertia->setRootView('seller');
    }

    public function show()
    {
        return $this->inertia->render('Login');
    }

    public function sendVerificationCode(Request $req) : Response
    {
        $code = rand(1000, 9999);

        // send SMS

        return response(Helper::responseTemplate(['code' => $code]), 200);
    }

    public function login(Request $req)
    {
        $credentials = $req->validate([
            'mobile' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();

            return response()->json(Auth::user(), 200);
        }
        return response()->json('unauthorized', 401);
    }
}
