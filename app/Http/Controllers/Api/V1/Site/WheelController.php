<?php

namespace App\Http\Controllers\Api\V1\Site;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Wheel;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\Helper;
use Carbon\Carbon;

class WheelController extends Controller
{
    public function index(Wheel $wheel): InertiaResponse
    {
        Inertia::setRootView('site');
        return Inertia::render('Index', [
            'wheel' => $wheel->load('userRequirements')
        ]);
    }

    public function stepOneLoign(Request $req): Response
    {
        if ($req->input('login_method') === 1) {

            $user = User::firstOrCreate(['mobile' => $req->input('mobile')]);
            auth()->login($user);

            // add user with to data


            return response(Helper::responseTemplate([
                'user' => $user->load('userRequirementValues')
            ], 'login'), 200);
        } else if ($req->input('login_method') === 2) {

            $user = User::firstOrCreate(['mobile' => $req->input('mobile')]);
            $code = rand(1000, 9999);

            if ($user->verificationCode) {
                if ($user->verificationCode->updated_at > Carbon::now()->subMinutes(2))
                    return response(Helper::responseTemplate(message: 'wait...'), 503);

                $user->verificationCode()->update([
                    'code' => $code
                ]);
            } else {
                $user->verificationCode()->create([
                    'code' => $code
                ]);
            }
            // send SMS

            return response(Helper::responseTemplate(message: 'success done'), 201);
        } else if ($req->input('login_method') === 3) {

            $token = Token::where($req->input('token'))->first();

            if (!$token)
                return response(Helper::responseTemplate(message: 'token not exist'), 401);

            $user = User::firstOrCreate(['mobile' => $req->input('mobile')]);
            $token->update('user_id');
            auth()->login($user);

            return response(Helper::responseTemplate([
                'user' => $user->load('userRequirementValues')
            ], 'login'), 200);
        }
    }

    public function enterVerificationCode(Request $req): Response
    {
        if (auth()->check())
            return response(Helper::responseTemplate(message: 'you are already logged in'), 400);

        $verificationCode = VerificationCode::whereHasMorph(
            'verificationCodeable',
            User::class,
            function ($query) use ($req) {
                $query->where('mobile', $req->input('mobile'));
            }
        )->first();

        if ($verificationCode->updated_at < Carbon::now()->subMinutes(2))
            return response(Helper::responseTemplate(message: 'expired'), 410);


        if ($verificationCode->code !== $req->input('code'))
            return response(Helper::responseTemplate(message: 'code not correct'), 401);

        $user = User::where('mobile', $req->input('mobile'))->first();
        auth()->login($user);

        return response(Helper::responseTemplate([
            'user' => $user->load('userRequirementValues')
        ], 'login'), 200);
    }
}
