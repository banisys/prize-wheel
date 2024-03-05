<?php

namespace App\Http\Controllers\Api\V1\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Plan;

class OrderController extends Controller
{
    public function store(Request $req): Response
    {
        // payment

        $sellerId = auth('seller')->user()->id;
        $plan = Plan::find($req->input('plan_id'));

        $latestOrder = Order::where('seller_id', $sellerId)->latest();

        if ($latestOrder) {
            $endAt = ($latestOrder->end_at > now()) ?
                $latestOrder->end_at->addDays($plan->days) :
                now()->addDays($plan->days);
        } else {
            $endAt = now()->addDays($plan->days);
        }

        Order::create([
            'seller_id' => $sellerId,
            'title' => $plan->title,
            'amount' => $plan->amount,
            'end_at' => $endAt,
        ]);


        return response(Helper::responseTemplate([], 'success done'), 201);
    }
}
