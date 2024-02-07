<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Wheel;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index(Wheel $wheel): InertiaResponse
    {
        $tokens = Token::where('wheel_id', $wheel->id)->paginate(20);

        Inertia::setRootView('seller');
        return Inertia::render('tokens/Index', [
            'tokens' => $tokens
        ]);
    }
}
