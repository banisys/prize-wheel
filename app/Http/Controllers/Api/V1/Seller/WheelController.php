<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Helper\General;
use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WheelController extends Controller
{
    public function store(Request $req): Response
    {
        $req->validate([
            'slice_num' => 'required'
        ]);

        $sliceNum = $req->input('slice_num');

        $slug = General::generateRandomString();
        while (Wheel::where('slug', $slug)->exists())
            $slug = General::generateRandomString();


        $wheel = Wheel::create([
            'seller_id' => auth('seller')->user()->id,
            'slug' => $slug,
            'slice_num' => $sliceNum
        ]);

        for ($x = 1; $x <= $sliceNum; $x++) {
            $wheel->slices()->create([
                'title' => ' آیتم' . $x,
                'probability' => round(100 / $sliceNum)
            ]);
        }

        return response(Helper::responseTemplate([
            'slug' => $slug,
        ], 'success done'), 201);
    }

    public function update(Wheel $wheel, Request $req): Response
    {
        $req->validate([
            'title' => 'required|min:1|max:80',
            'description' => 'min:1|max:380',
            'try' => 'required|max:1',
            'days_left_to_try_again' => 'max:3',
            'login_method' => 'required',
            'slices' => 'required',
            'slices.*.title' => 'max:80',
        ]);

        $wheel->update($req->toArray());

        $wheel->userRequirements()->sync($req->input('user_requirements'));

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

    public function search(Wheel $wheel, Request $req): Response
    {
        $users = User::whereHas('prizes', function ($q) use ($wheel) {
            $q->where('wheel_id', $wheel->id);
        })->with([
            'prizes' => function ($q) use ($wheel) {
                $q->where('wheel_id', $wheel->id);
            },
            'userRequirementValues' => function ($q) use ($wheel) {
                $q->where('wheel_id', $wheel->id)->with('userRequirement');
            }
        ])
            ->when($req->input('mobile'), function ($q) use ($req) {
                $q->where('mobile', $req->input('mobile'));
            })
            ->latest()->paginate(10);

        return response(Helper::responseTemplate([
            'users' => $users,
        ], 'success done'), 200);
    }

    public function calPrizeStatistics(Wheel $wheel, Request $req)
    {
    }

    public function destroy(Wheel $wheel)
    {
        $wheel->slices()->delete();
        $wheel->userRequirements()->detach();
        $wheel->userRequirementValues()->delete();
        $wheel->dateLeftToTryAgain()->delete();
        $wheel->discountCodes()->delete();
        $wheel->prizes()->delete();
        $wheel->tokens()->delete();
        $wheel->delete();

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }
}
