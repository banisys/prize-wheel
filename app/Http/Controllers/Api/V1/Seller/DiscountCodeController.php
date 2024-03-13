<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Http\Controllers\Api\V1\Helper;
use App\Models\Slice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class DiscountCodeController extends Controller
{
    public function store(Request $req): Response
    {
        $req->validate([
            'slice_id' => 'required|integer',
            'file' => 'file',
        ]);

        $file = Excel::toArray([], $req->file('file'));

        if (!$file[0][0][0])
            return response(Helper::responseTemplate(message: 'The file data is invalid'), 400);

        DB::transaction(function () use ($req, $file) {

            DiscountCode::where([
                'slice_id' => $req->input('slice_id'),
            ])->whereNull('user_id')->delete();

            foreach ($file[0] as $item) {
                DiscountCode::updateOrCreate(
                    [
                        'slice_id' => $req->input('slice_id'),
                        'code' => $item[0]
                    ],
                    [
                        'slice_id' => $req->input('slice_id'),
                        'code' => $item[0]
                    ]
                );
            }
        });

        $discountCodeCount = DiscountCode::where([
            'slice_id' => $req->input('slice_id'),
            'user_id' => null
        ])->count();


        Slice::find($req->input('slice_id'))->update([
            'inventory' => $discountCodeCount
        ]);

        $slice = Slice::withCount('discountCodesNotUsed')->find($req->input('slice_id'));



        $discountCodesExists = DiscountCode::where('slice_id', $req->input('slice_id'))->exists();

        return response(Helper::responseTemplate([
            'slice' => $slice,
            'discount_codes_exists' => $discountCodesExists
        ], 'success done'), 201);
    }

    public function fetch($slice_id): Response
    {
        $discountCodes = DiscountCode::with('user')
            ->where('slice_id', $slice_id)->paginate(20);

        return response(Helper::responseTemplate([
            'discount_codes' => $discountCodes
        ], 'success done'), 201);
    }

    public function destroy($slice_id): Response
    {
        DiscountCode::where('slice_id', $slice_id)->whereNull('user_id')->delete();

        Slice::find($slice_id)->update([
            'inventory' => null
        ]);

        $discountCodes = DiscountCode::with('user')
            ->where('slice_id', $slice_id)->paginate(20);

        $slice = Slice::withCount('discountCodesNotUsed')->find($slice_id);

        $discountCodesExists = DiscountCode::where('slice_id', $slice_id)->exists();


        return response(Helper::responseTemplate([
            'discount_codes' => $discountCodes,
            'slice' => $slice,
            'discount_codes_exists' => $discountCodesExists
        ], 'success done'), 201);
    }
}
