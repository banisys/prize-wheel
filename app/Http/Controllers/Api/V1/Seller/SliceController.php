<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Api\V1\Helper;
use App\Http\Controllers\Controller;
use App\Models\Slice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SliceController extends Controller
{
    public function update(Slice $slice, Request $req): Response
    {
        $slice->update($req->toArray());

        return response(Helper::responseTemplate(message: 'success done'), 201);
    }
}
