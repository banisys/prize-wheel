<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Helper\General;
use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exports\TokensExport;
use App\Models\Wheel;
use Maatwebsite\Excel\Facades\Excel;

class TokenController extends Controller
{
    public function store(Request $req, Wheel $wheel): Response
    {
        for ($x = 0; $x < $req->input('token_num'); $x++) {
            Token::create([
                'wheel_id' => $wheel->id,
                'value' => General::generateRandomString(),
                'end_at' => $req->input('end_at')
            ]);
        }

        $tokens = Token::where('wheel_id', $wheel->id)->with('user')->paginate(20);

        return response(Helper::responseTemplate([
            'tokens' => $tokens
        ], 'success done'), 200);
    }

    public function destroyNotUsed(Wheel $wheel): Response
    {
        Token::where('wheel_id', $wheel->id)->whereNull('user_id')->delete();

        $tokens = Token::where('wheel_id', $wheel->id)->with('user')->paginate(20);

        return response(Helper::responseTemplate([
            'tokens' => $tokens
        ], 'success done'), 201);
    }

    public function downloadExcel(Wheel $wheel)
    {
        return Excel::download(new TokensExport($wheel->id), 'tokens.xlsx');
    }

    public function search(Request $req, Wheel $wheel): Response
    {
        $tokens = Token::where('wheel_id', $wheel->id)
            ->when($req->input('token'), function ($q) use ($req) {
                $q->where('value', $req->input('token'));
            })
            ->when($req->input('mobile'), function ($q) use ($req) {
                $q->whereHas('user', function ($q) use ($req) {
                    $q->where('mobile', $req->input('mobile'));
                });
            })->with('user')->paginate(20);

        return response(Helper::responseTemplate([
            'tokens' => $tokens
        ], 'success done'), 201);
    }
}
