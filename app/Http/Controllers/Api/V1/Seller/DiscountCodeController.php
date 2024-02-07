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
            'wheel_id' => 'required|integer',
            'slice_id' => 'required|integer',
            'file' => 'file',
        ]);

        $file = Excel::toArray([], $req->file('file'));

        if (!$file[0][0][0])
            return response(Helper::responseTemplate(message: 'The file data is invalid'), 400);

        DB::transaction(function () use ($req, $file) {

            Slice::where('wheel_id', $req->input('wheel_id'))
                ->update([
                    'inventory' => null
                ]);


            DiscountCode::where([
                'wheel_id' => $req->input('wheel_id'),
                'slice_id' => $req->input('slice_id'),
            ])->whereNull('user_id')->delete();


            foreach ($file[0] as $item) {
                DiscountCode::updateOrCreate(
                    [
                        'wheel_id' => $req->input('wheel_id'),
                        'slice_id' => $req->input('slice_id'),
                        'code' => $item[0]

                    ],
                    [
                        'wheel_id' => $req->input('wheel_id'),
                        'slice_id' => $req->input('slice_id'),
                        'code' => $item[0]
                    ]
                );
            }
        });

        $slice = Slice::withCount('discountCodes')->find($req->input('slice_id'));

        return response(Helper::responseTemplate([
            'slice' => $slice
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

        $discountCodes = DiscountCode::with('user')
            ->where('slice_id', $slice_id)->paginate(20);

        $slice = Slice::withCount('discountCodes')->find($slice_id);

        return response(Helper::responseTemplate([
            'discount_codes' => $discountCodes,
            'slice' => $slice
        ], 'success done'), 201);
    }
}
