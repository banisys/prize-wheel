<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wheel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'title',
        'slug',
        'slice_num',
        'try',
        'try_share',
        'days_left_to_try_again',
        'start_at',
        'end_at',
        'login_method'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the slices for the wheel.
     */
    public function slices(): HasMany
    {
        return $this->hasMany(Slice::class);
    }

    /**
     * Get the active slices for the wheel.
     */
    public function popularSlices(): HasMany
    {
        return $this->hasMany(Slice::class)->where('status', 1);
    }

    /**
     * The roles that belong to the user.
     */
    public function userRequirements(): BelongsToMany
    {
        return $this->belongsToMany(UserRequirement::class);
    }

    /**
     * Get the user requirement values for the wheel.
     */
    public function userRequirementValues(): HasMany
    {
        return $this->hasMany(UserRequirementValue::class);
    }

    /**
     * Get the date associated with the wheel.
     */
    public function dateLeftToTryAgain(): HasOne
    {
        return $this->hasOne(DateLeftToTryAgain::class);
    }

    /**
     * Get the discount code for the wheel.
     */
    public function discountCodes(): HasMany
    {
        return $this->hasMany(DiscountCode::class);
    }

    /**
     * Get the prizes for the wheel.
     */
    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class);
    }

    /**
     * Get the prizes for the wheel.
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
