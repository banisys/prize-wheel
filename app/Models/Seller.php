<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Seller extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mobile', 'full_name', 'store_name'];

    /**
     * Get the seller's verificationCode.
     */
    public function verificationCode(): MorphOne
    {
        return $this->morphOne(VerificationCode::class, 'verification_codeable', 'codeable_type', 'codeable_id');
    }
}
