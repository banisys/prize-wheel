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
use App\Models\DateLeftToTryAgain;
use App\Models\Prize;
use App\Models\UserRequirement;
use App\Models\UserRequirementValue;
use Carbon\Carbon;

class WheelController extends Controller
{
    public function index($wheel): InertiaResponse
    {
        Inertia::setRootView('site');

        $wheel = Wheel::where('slug', $wheel)->with([
            'slices' => function ($query) {
                $query->select(
                    'id',
                    'wheel_id',
                    'title',
                    'priority'
                );
            },
            'userRequirements',
            'dateLeftToTryAgain'
        ])->firstOrFail();

        $statusDate = $this->checkStatusDate($wheel);

        if ($statusDate['not_started'] || $statusDate['finished'])
            return Inertia::render('Index', [
                ...$statusDate,
                'wheel' => $wheel
            ]);



        $user = auth()->user();

        $UserRequirementValueExists = UserRequirementValue::where([
            'user_id' => optional($user)->id,
            'wheel_id' => $wheel->id,
        ])->exists();

        return Inertia::render('Index', [
            'wheel' => $wheel->makeHidden(['created_at', 'updated_at'])->toArray(),
            'user' => $user,
            'user_requirement_value_exists' => $UserRequirementValueExists,
        ]);
    }

    public function loign(Request $req): Response
    {
        $req->validate([
            'wheel_id' => 'required',
            'login_method' => 'required',
            'mobile' => 'required',
        ]);

        if ($req->input('login_method') === 1) {

            $user = User::firstOrCreate(['mobile' => $req->input('mobile')]);
            auth()->login($user);

            $userRequirementValueExists = UserRequirementValue::where([
                'user_id' => $user->id,
                'wheel_id' => $req->input('wheel_id'),
            ])->exists();

            return response(Helper::responseTemplate([
                'user_requirement_value_exists' => $userRequirementValueExists,
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

            $token = Token::where($req->input('token'))->whereNull('user_id')->first();

            if (!$token)
                return response(Helper::responseTemplate(message: 'token not exist'), 401);

            $user = User::firstOrCreate(['mobile' => $req->input('mobile')]);
            $token->update(['user_id' => $user->id]);
            auth()->login($user);

            $userRequirementValueExists = UserRequirementValue::where([
                'user_id' => $user->id,
                'wheel_id' => $req->input('wheel_id'),
            ])->exists();

            return response(Helper::responseTemplate([
                'user_requirement_value_exists' => $userRequirementValueExists
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
            'priority' => 'required'
        ]);

        $wheel = Wheel::find($req->input('wheel_id'));
        $remainTry = $this->remainTryCalculation($wheel);

        if (!$remainTry)
            return response(Helper::responseTemplate('there is no retry'), 400);


        $userId = auth()->user()->id;

        Prize::create([
            'user_id' => $userId,
            'wheel_id' => $req->input('wheel_id'),
            'title' => $req->input('title'),
            'priority' => $req->input('priority')
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

    private function checkStatusDate($wheel)
    {
        $status = [
            'not_started' => 0,
            'finished' => 0
        ];

        if ($wheel->start_at !== null && $wheel->start_at > now()) $status['not_started'] = 1;
        if ($wheel->end_at !== null && $wheel->end_at < now()) $status['finished'] = 1;

        return $status;
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

        return $wheel->try - $prizeCount;
    }
}
