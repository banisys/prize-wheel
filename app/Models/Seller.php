<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'seller';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'mobile',
        'full_name',
        'store_name',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the seller's verificationCode.
     */
    public function verificationCode(): MorphOne
    {
        return $this->morphOne(VerificationCode::class, 'verification_codeable', 'codeable_type', 'codeable_id');
    }
}
