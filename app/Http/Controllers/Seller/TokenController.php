<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Wheel;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TokenController extends Controller
{
    public function index(Wheel $wheel): InertiaResponse
    {
        $tokens = Token::where('wheel_id', $wheel->id)->with('user')->paginate(20);

        Inertia::setRootView('layout-inertia.seller');
        return Inertia::render('tokens/Index', [
            'slug' => $wheel->slug,
            'tokens' => $tokens
        ]);
    }
}
