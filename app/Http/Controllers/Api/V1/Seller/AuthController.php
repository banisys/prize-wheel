<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Helper;
use App\Models\Seller;
use Illuminate\Http\Response;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function show()
    {
        Inertia::setRootView('seller');

        return Inertia::render('Login');
    }

    public function sendVerificationCode(Request $req): Response
    {
        $req->validate([
            'mobile' => 'required',
        ]);

        $seller = Seller::updateOrCreate(['mobile' => $req->mobile]);

        $code = rand(1000, 9999);

        $seller->verificationCode()->updateOrCreate([
            'codeable_type' => Seller::class,
            'codeable_id' => $seller->id,
        ], [
            'code' => $code
        ]);

        // send SMS

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

    public function showCode()
    {
        Inertia::setRootView('seller');

        return Inertia::render('Code');
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
