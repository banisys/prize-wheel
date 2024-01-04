<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
    ];

    /**
     * Get the seller's verificationCode.
     */
    public function verificationCode(): MorphOne
    {
        return $this->morphOne(VerificationCode::class, 'verification_codeable', 'codeable_type', 'codeable_id');
    }

    /**
     * Get the userRequirement for the user.
     */
    public function userRequirementValues(): HasMany
    {
        return $this->hasMany(UserRequirementValue::class);
    }
}
