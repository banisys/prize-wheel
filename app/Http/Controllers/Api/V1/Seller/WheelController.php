<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Helper\General;
use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;
use Illuminate\Http\Response;

class WheelController extends Controller
{
    public function index(): InertiaResponse
    {
        Inertia::setRootView('seller');

        return Inertia::render('wheels/Index');
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

    public function edit(Wheel $wheel): InertiaResponse
    {
        Inertia::setRootView('seller');

        return Inertia::render('wheels/Edit', $wheel);
    }


}
