<?php

namespace App\Exports;

use App\Models\Token;
use Maatwebsite\Excel\Concerns\FromCollection;

class TokensExport implements FromCollection
{
    private $wheelId;

    public function __construct($wheelId)
    {
        $this->wheelId = $wheelId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Token::select([
            'value'
        ])->where('wheel_id', $this->wheelId)->whereNull('user_id')->get();
    }
}
