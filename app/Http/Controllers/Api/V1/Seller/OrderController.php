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

        info($req);

        $plan = Plan::find($req->input('plan_id'));

        Order::create([
            'seller_id' => auth('seller')->user()->id,
            'title' => $plan->title,
            'amount' => $plan->amount,
            'end_at' => now()->addDays($plan->days),
        ]);


        return response(Helper::responseTemplate([

        ], 'success done'), 201);
    }
}
