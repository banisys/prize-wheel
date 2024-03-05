<?php

namespace App\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Http\Controllers\Controller;
use App\Models\UserRequirementValue;
use App\Models\Wheel;

class WheelController extends Controller
{
    public function wheelShow($wheel): InertiaResponse
    {
        Inertia::setRootView('layout-inertia.site');

        $wheel = Wheel::where('slug', $wheel)->with([
            'slices' => function ($query) {
                $query->select(
                    'id',
                    'wheel_id',
                    'title',
                    'probability'
                );
            },
            'popularSlices' => function ($query) {
                $query->select(
                    'id',
                    'wheel_id',
                    'title'
                )->withCount(['prizes']);
            },
            'userRequirements',
            'dateLeftToTryAgain'
        ])->firstOrFail();

        $orderExist = Order::where('seller_id', $wheel->seller_id)
            ->where('end_at', '>', now())->exists();

        if ($orderExist)
            return Inertia::render('wheels/Index', ['wheel' => ['status' => 0]]);

        $statusDate = $this->checkStatusDate($wheel);

        if ($statusDate['not_started'] || $statusDate['finished'])
            return Inertia::render('wheels/Index', [
                ...$statusDate,
                'wheel' => $wheel->only(['status'])
            ]);

        $user = auth()->user();

        $UserRequirementValueExists = UserRequirementValue::where([
            'user_id' => optional($user)->id,
            'wheel_id' => $wheel->id,
        ])->exists();

        return Inertia::render('wheels/Index', [
            'wheel' => $wheel->makeHidden(['created_at', 'updated_at'])->toArray(),
            'user' => $user,
            'user_requirement_value_exists' => $UserRequirementValueExists,
        ]);
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
}
