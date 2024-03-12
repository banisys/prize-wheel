<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\Prize;
use App\Models\Slice;
use App\Models\User;
use App\Models\UserRequirement;
use App\Models\Wheel;

class WheelController extends Controller
{
    public function index(): InertiaResponse
    {
        $wheels = Wheel::where('seller_id', auth('seller')->id())
            ->with('seller')->get();

        Inertia::setRootView('layout-inertia.seller');
        return Inertia::render('wheels/Index', [
            'wheels' => $wheels
        ]);
    }

    public function edit($wheel): InertiaResponse
    {
        $wheel = Wheel::where('slug', $wheel)->with([
            'slices' => function ($query) {
                $query->select(['id', 'wheel_id', 'title']);
            },
            'userRequirements' => function ($query) {
                $query->select('id');
            },
            'seller' => function ($query) {
                $query->select(['id', 'sms_number']);
            }
        ])->firstOrFail();

        Inertia::setRootView('layout-inertia.seller');

        return Inertia::render('wheels/Edit', [
            'wheel' => $wheel->makeHidden(['created_at', 'updated_at'])->toArray(),
            'userRequirements' => UserRequirement::all(),
        ]);
    }

    public function show($wheel): InertiaResponse
    {
        $wheel = Wheel::select(['seller_id', 'slug', 'title', 'login_method'])
            ->where('slug', $wheel)->with([
                'seller' => function ($query) {
                    $query->select(['id', 'sms_number']);
                }
            ])->firstOrFail();

        $users = User::whereHas('prizes', function ($q) use ($wheel) {
            $q->where('wheel_id', $wheel->id);
        })->with([
            'prizes' => function ($q) use ($wheel) {
                $q->where('wheel_id', $wheel->id);
            },
            'userRequirementValues' => function ($q) use ($wheel) {
                $q->where('wheel_id', $wheel->id)->with('userRequirement');
            }
        ])->latest()->paginate(10);

        $prizes = Prize::select('title')->where('wheel_id', $wheel->id)->get()->groupBy('title');

        $sumNumberOfEachPrize = [];
        foreach ($prizes as $key => $prize) $sumNumberOfEachPrize[$key] = count($prize);

        Inertia::setRootView('layout-inertia.seller');
        return Inertia::render('wheels/Show', [
            'wheel' => $wheel,
            'users' => $users,
            'sum_number_of_each_prize' => $sumNumberOfEachPrize,
        ]);
    }

    public function editSlice($slice): InertiaResponse
    {
        Inertia::setRootView('layout-inertia.seller');

        $slice = Slice::withCount([
            'discountCodes' => function ($query) {
                $query->whereNull('user_id');
            }
        ])->findOrfail($slice);

        $sumProbability = Slice::where('wheel_id', $slice->wheel_id)->sum('probability');

        $discountCodesExists= DiscountCode::where('slice_id', $slice->id)->exists();

        return Inertia::render('slices/Edit', [
            'slice' => $slice->makeHidden(['created_at', 'updated_at'])->toArray(),
            'sum_probability' => $sumProbability,
            'discount_codes_exists' => $discountCodesExists
        ]);
    }
}
