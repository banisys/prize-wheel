<?php

namespace App\Http\Controllers\Api\V1\Site;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Wheel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\Helper;
use App\Models\DateLeftToTryAgain;
use App\Models\Prize;
use App\Models\Subuser;
use App\Models\UserRequirement;
use App\Models\UserRequirementValue;
use Carbon\Carbon;

class WheelController extends Controller
{
    public function loign(Request $req): Response
    {
        $req->validate([
            'wheel_id' => 'required',
            'login_method' => 'required',
            'mobile' => 'required',
        ]);

        $introducer = null;

        if ($req->input('introducer_code')) {
            $introducer = User::where('code', $req->input('introducer_code'))->first();

            if (!$introducer->id || $introducer->mobile === $req->input('mobile'))
                return response(Helper::responseTemplate(message: 'introducer not exist'), 401);
        }

        if ($req->input('login_method') === 1) {

            $userRequirementValueExists = $this->loginMethod1($req, $introducer);

            return response(Helper::responseTemplate([
                'user_requirement_value_exists' => $userRequirementValueExists,
                'user' => auth()->user()
            ], 'login'), 200);
        } else if ($req->input('login_method') === 2) {

            $this->loginMethod2($req);

            return response(Helper::responseTemplate(message: 'success done'), 201);
        } else if ($req->input('login_method') === 3) {

            $userRequirementValueExists = $this->loginMethod3($req, $introducer);

            return response(Helper::responseTemplate([
                'user_requirement_value_exists' => $userRequirementValueExists,
                'user' => auth()->user()
            ], 'login'), 200);
        }
    }

    public function checkVerificationCode(Request $req): Response
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

        if ($req->input('introducer_code')) {
            $introducer = User::where('code', $req->input('introducer_code'))->first();
            $this->storeIntroducer($req, $user, $introducer);
        }

        auth()->login($user);

        return response(Helper::responseTemplate([
            'user' => $user->load('userRequirementValues')
        ], 'login'), 200);
    }

    public function userRequirementStore(Request $req): Response
    {
        $req->validate([
            'user_requirement' => 'required',
            'user_requirement.name' => 'max:30',
            'user_requirement.gender' => 'max:1',
            'user_requirement.email' => 'max:60'
        ]);

        $userId = auth()->user()->id;

        $UserRequirementValueExists = UserRequirementValue::where([
            'user_id' => $userId,
            'wheel_id' => $req->input('wheel_id'),
        ])->exists();

        if ($UserRequirementValueExists)
            return response(Helper::responseTemplate(message: 'user requirement value exists'), 400);

        foreach ($req->input('user_requirement') as $key => $item) {
            UserRequirementValue::create([
                'user_id' => $userId,
                'user_requirement_id' => UserRequirement::where('name', $key)->pluck('id')->first(),
                'wheel_id' => $req->input('wheel_id'),
                'value' => $item,
            ]);
        }

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

    public function prizeStore(Request $req): Response
    {
        $req->validate([
            'wheel_id' => 'required',
            'title' => 'required',
            'probability' => 'required'
        ]);

        $wheel = Wheel::find($req->input('wheel_id'));
        $remainTry = $this->remainTryCalculation($wheel);

        if (!$remainTry)
            return response(Helper::responseTemplate('there is no retry'), 400);


        $userId = auth()->user()->id;

        Prize::create([
            'user_id' => $userId,
            'wheel_id' => $req->input('wheel_id'),
            'slice_id' => $req->input('id'),
            'title' => $req->input('title'),
            'probability' => $req->input('probability')
        ]);

        $prizes = Prize::where([
            'user_id' => $userId,
            'wheel_id' => $wheel->id
        ])->latest()->paginate(10);

        return response(Helper::responseTemplate([
            'remain_try' => $remainTry - 1,
            'prizes' => $prizes
        ], 'success done'), 201);
    }

    public function wheelDataFetch(Wheel $wheel): Response
    {
        $remainTry = $this->remainTryCalculation($wheel);

        $prizes = Prize::where([
            'user_id' => auth()->user()->id,
            'wheel_id' => $wheel->id
        ])->latest()->paginate(10);

        return response(Helper::responseTemplate([
            'remain_try' => $remainTry,
            'prizes' => $prizes
        ], 'success done'), 200);
    }

    private function remainTryCalculation($wheel)
    {
        if ($wheel->days_left_to_try_again) {

            $userId = auth()->user()->id;
            $dateLeftToTryAgain = DateLeftToTryAgain::where([
                'user_id' => $userId,
                'wheel_id' => $wheel->id,
            ])->pluck('date_at')->first();

            if (!$dateLeftToTryAgain) {
                DateLeftToTryAgain::create([
                    'user_id' => $userId,
                    'wheel_id' => $wheel->id,
                    'date_at' => now()->addDays($wheel->days_left_to_try_again)
                ]);
            } else {
                if ($dateLeftToTryAgain < now()) {
                    Prize::where([
                        'user_id' => $userId,
                        'wheel_id' => $wheel->id,
                    ])->update([
                        'old' => 1
                    ]);

                    DateLeftToTryAgain::where([
                        'user_id' => $userId,
                        'wheel_id' => $wheel->id,
                    ])->update([
                        'date_at' => now()->addDays($wheel->days_left_to_try_again)
                    ]);
                }
            }
        }

        $prizeCount = Prize::where([
            'user_id' => auth()->user()->id,
            'wheel_id' => $wheel->id,
        ])->whereNull('old')->count();

        $subUserTryCount = Subuser::where([
            'wheel_id' => $wheel->id,
            'user_id' => auth()->user()->id,
        ])->count('try');

        return ($wheel->try - $prizeCount) + round($subUserTryCount);
    }

    private function storeIntroducer($req, $user, $introducer)
    {
        $subuserExists = Subuser::where([
            'wheel_id' => $req->input('wheel_id'),
            'sub_id' => $user->id,
        ])->exists();

        if (!$subuserExists) {
            $wheel = Wheel::find($req->input('wheel_id'));
            Subuser::create([
                'wheel_id' => $req->input('wheel_id'),
                'user_id' => $introducer->id,
                'sub_id' => $user->id,
                'try' => $wheel->try_share
            ]);
        }
    }

    private function loginMethod1($req, $introducer)
    {
        $user = User::firstOrCreate(
            ['mobile' => $req->input('mobile')],
            ['code' => rand(100000, 999999)]
        );

        $userRequirementValueExists = UserRequirementValue::where([
            'user_id' => $user->id,
            'wheel_id' => $req->input('wheel_id'),
        ])->exists();

        if ($req->input('introducer_code'))
            $this->storeIntroducer($req, $user, $introducer);

        auth()->login($user);

        return $userRequirementValueExists;
    }

    private function loginMethod2($req)
    {
        $user = User::firstOrCreate(
            ['mobile' => $req->input('mobile')],
            ['code' => rand(100000, 999999)]
        );
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
    }

    private function loginMethod3($req, $introducer)
    {
        $token = Token::where('value', $req->input('token'))->first();

        if (!$token)
            return response(Helper::responseTemplate(message: 'token not exist'), 401);

        if ($token->end_at < now())
            return response(Helper::responseTemplate(message: 'token is expired'), 410);

        if ($token->user_id) {
            $user = User::find($token->user_id);

            if ($user->mobile !== $req->input('mobile'))
                return response(Helper::responseTemplate(message: 'mobile is not correct'), 400);
        } else {
            $user = User::firstOrCreate(
                ['mobile' => $req->input('mobile')],
                ['code' => rand(100000, 999999)]
            );
            $token->update(['user_id' => $user->id]);
        }

        $userRequirementValueExists = UserRequirementValue::where([
            'user_id' => $user->id,
            'wheel_id' => $req->input('wheel_id'),
        ])->exists();

        if ($req->input('introducer_code'))
            $this->storeIntroducer($req, $user, $introducer);

        auth()->login($user);

        return $userRequirementValueExists;
    }
}
