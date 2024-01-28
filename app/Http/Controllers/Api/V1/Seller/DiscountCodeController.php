<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Http\Controllers\Api\V1\Helper;
use App\Models\Slice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class DiscountCodeController extends Controller
{
    public function store(Request $req)
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

        $slices = Slice::where('wheel_id', $req->input('wheel_id'))
            ->withCount('discountCodes')->get();

        return response(Helper::responseTemplate([
            'slices' => $slices
        ], 'success done'), 201);
    }

    public function fetch($slice_id)
    {
        $discountCodes = DiscountCode::with('user')
            ->where('slice_id', $slice_id)->paginate(20);

        return response(Helper::responseTemplate([
            'discount_codes' => $discountCodes
        ], 'success done'), 201);
    }

    public function destroy($slice_id)
    {
        DiscountCode::where('slice_id', $slice_id)->whereNull('user_id')->delete();

        return $this->fetch($slice_id);
    }
}
