<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
    ];

    /**
     * Get the parent verificationCodeable model (user or seller).
     */
    public function verificationCodeable(): MorphTo
    {
        return $this->morphTo('verification_codes', 'codeable_type', 'codeable_type');
    }
}
