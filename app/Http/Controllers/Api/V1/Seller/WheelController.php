<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Helper\General;
use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\Slice;
use App\Models\UserRequirement;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;
use Illuminate\Http\Response;

class WheelController extends Controller
{
    public function index(): InertiaResponse
    {
        $wheels = Wheel::where('seller_id', auth('seller')->id())->get();

        Inertia::setRootView('seller');
        return Inertia::render('wheels/Index', [
            'wheels' => $wheels
        ]);
    }

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
                'priority' => round(100 / $sliceNum)
            ]);
        }

        return response(Helper::responseTemplate([
            'slug' => $slug,
        ], 'success done'), 201);
    }

    public function edit($wheel): InertiaResponse
    {
        $wheel = Wheel::where('slug', $wheel)->with([
            'slices' => function ($query) {
                $query->select(
                    'id',
                    'wheel_id',
                    'title',
                    'priority'
                );
            },
            'userRequirements' => function ($query) {
                $query->select('id');
            },
        ])->first();

        Inertia::setRootView('seller');

        return Inertia::render('wheels/Edit', [
            'wheel' => $wheel->makeHidden(['created_at', 'updated_at'])->toArray(),
            'userRequirements' => UserRequirement::all()
        ]);
    }

    public function update(Wheel $wheel, Request $req): Response
    {
        $req->validate([
            'title' => 'required|min:1|max:80',
            'try' => 'required|max:1',
            'days_left_to_try_again' => 'max:3',
            'login_method' => 'required',
            'slices' => 'required',
            'slices.*.title' => 'max:80',
            'user_requirements' => 'required',
        ]);

        $wheel->update($req->toArray());

        foreach ($req->input('slices') as $slice) {
            Slice::find($slice['id'])->update($slice);
        }

        $wheel->userRequirements()->sync($req->input('user_requirements'));

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }

}
