<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSms extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'seller_id',
        'number',
        'payment',
    ];

    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'];
    }
}
