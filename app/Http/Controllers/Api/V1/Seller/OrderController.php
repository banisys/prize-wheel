<?php

namespace App\Http\Controllers\Api\V1\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\OrderPlan;
use App\Models\OrderSms;
use App\Models\Plan;
use App\Models\Setting;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function storePlan(Request $req): Response
    {
        // payment

        $sellerId = auth('seller')->user()->id;
        $plan = Plan::find($req->input('plan_id'));

        $latestOrder = OrderPlan::where('seller_id', $sellerId)->latest()->first();

        if ($latestOrder) {
            $endAt = ($latestOrder->end_at > now()) ?
                (new Carbon($latestOrder->end_at))->addDays($plan->days) :
                now()->addDays($plan->days);
        } else {
            $endAt = now()->addDays($plan->days);
        }

        OrderPlan::create([
            'seller_id' => $sellerId,
            'title' => $plan->title,
            'payment' => $plan->amount,
            'end_at' => $endAt,
        ]);

        return response(Helper::responseTemplate([], 'success done'), 201);
    }

    public function storeSms(Request $req): Response
    {
        $payment = (int) Setting::where('key', 'sms_amount')->pluck('value')->shift() * (int) $req->input('number');

        $seller = auth('seller')->user();

        OrderSms::create([
            'seller_id' => $seller->id,
            'number' => $req->input('number'),
            'payment' => $payment,
        ]);

        Seller::update([
            'sms_number' => $req->input('number') + $seller->sms_number
        ]);

        return response(Helper::responseTemplate([], 'success done'), 201);
    }
}
