<?php

namespace App\Http\Controllers\Api\V1;

class Helper
{
    public static function responseTemplate($data = [], $message = '')
    {
        return [
            'message' => $message,
            'data' => $data
        ];
    }
}
