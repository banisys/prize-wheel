<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderPlan;
use App\Models\OrderSms;
use App\Models\Plan;
use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function plan(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('layout-inertia.seller');

        $orders = OrderPlan::where('seller_id', auth('seller')->user()->id)->latest()->paginate(10);

        return Inertia::render('orders/Plan', [
            'plans' => Plan::get(),
            'orders' => $orders
        ]);
    }

    public function sms(): InertiaResponse | RedirectResponse
    {
        Inertia::setRootView('layout-inertia.seller');

        $orders = OrderSms::where('seller_id', auth('seller')->user()->id)->latest()->paginate(10);

        return Inertia::render('orders/Sms', [
            'sms_amount' => Setting::where('key', 'sms_amount')->pluck('value')->shift(),
            'orders' => $orders
        ]);
    }
}
