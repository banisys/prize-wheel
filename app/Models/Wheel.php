<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'days_left_to_try_again',
        'expiration_at',
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
     * The roles that belong to the user.
     */
    public function userRequirements(): BelongsToMany
    {
        return $this->belongsToMany(UserRequirement::class);
    }
}
