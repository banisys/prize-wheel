<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Helper;
use App\Models\Seller;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        Inertia::setRootView('seller');

        return Inertia::render('Login');
    }

    public function sendVerificationCode(Request $req): Response
    {
        $req->validate([
            'mobile' => 'required',
        ]);

        $seller = Seller::firstOrCreate(['mobile' => $req->mobile]);

        $code = rand(1000, 9999);

        if ($seller->verificationCode) {
            if ($seller->verificationCode->updated_at > Carbon::now()->subMinutes(2)) {
                return response(Helper::responseTemplate(message: 'wait...'), 500);
            }

            $seller->verificationCode()->update([
                'code' => $code
            ]);
            // send SMS

        } else {
            $seller->verificationCode()->create([
                'code' => $code
            ]);
            // send SMS
        }

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

    public function showCode()
    {
        Inertia::setRootView('seller');

        return Inertia::render('Code');
    }

    public function enterVerificationCode(Request $req): Response
    {
        $verificationCode = VerificationCode::whereHasMorph(
            'verificationCodeable',
            Seller::class,
            function ($query) use ($req) {
                $query->where('mobile', $req->input('mobile'));
            }
        )->pluck('code')->first();

        if ($verificationCode !== $req->input('code'))
            return response(Helper::responseTemplate(message: 'code not correct'), 500);

        $seller = Seller::where('mobile', $req->input('mobile'))->first();
        auth('seller')->login($seller);

        return response(Helper::responseTemplate(
            message: $seller->password ? 'password set' : 'password not set'
        ), 200);
    }

    public function showPassword()
    {
        Inertia::setRootView('seller');

        $seller = auth('seller')->user()->only(['id', 'mobile']);

        return Inertia::render('Password', [
            'seller' => $seller
        ]);
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
}
