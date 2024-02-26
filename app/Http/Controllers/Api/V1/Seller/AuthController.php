<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Helper;
use App\Models\Seller;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function sendVerificationCode(Request $req): Response
    {
        if (auth('seller')->check())
            return response(Helper::responseTemplate(message: 'you are already logged in'), 400);

        $req->validate([
            'mobile' => 'required',
        ]);

        $seller = Seller::firstOrCreate(['mobile' => $req->input('mobile')]);

        if ($seller->password && !$req->input('password_forgot'))
            return response(Helper::responseTemplate(message: 'password is set'), 200);


        $code = rand(1000, 9999);

        if ($seller->verificationCode) {
            if ($seller->verificationCode->updated_at > Carbon::now()->subMinutes(2))
                return response(Helper::responseTemplate(message: 'wait...'), 503);

            $seller->verificationCode()->update([
                'code' => $code
            ]);
        } else {
            $seller->verificationCode()->create([
                'code' => $code
            ]);
        }
        // send SMS

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

    public function checkVerificationCode(Request $req): Response
    {
        if (auth('seller')->check())
            return response(Helper::responseTemplate(message: 'you are already logged in'), 400);

        $verificationCode = VerificationCode::whereHasMorph(
            'verificationCodeable',
            Seller::class,
            function ($query) use ($req) {
                $query->where('mobile', $req->input('mobile'));
            }
        )->first();

        if ($verificationCode->updated_at < Carbon::now()->subMinutes(2))
            return response(Helper::responseTemplate(message: 'expired'), 410);


        if ($verificationCode->code !== $req->input('code'))
            return response(Helper::responseTemplate(message: 'code not correct'), 401);

        $seller = Seller::where('mobile', $req->input('mobile'))->first();
        auth('seller')->login($seller);

        return response(Helper::responseTemplate(
            message: $seller->password ? 'password set' : 'password not set'
        ), 200);
    }

    public function passwordStore(Request $req): Response
    {
        $req->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        auth('seller')->user()->update([
            'password' => $req->input('password')
        ]);

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

    public function login(Request $req): Response
    {
        if (auth('seller')->check())
            return response(Helper::responseTemplate(message: 'you are already logged in'), 400);

        $credentials = $req->validate([
            'mobile' => ['required'],
            'password' => ['required']
        ]);

        if (auth('seller')->attempt($credentials)) {
            return response(Helper::responseTemplate(message: 'login'), 200);
        } else {
            return response(Helper::responseTemplate(message: 'username and password do not match'), 401);
        }
    }
}
