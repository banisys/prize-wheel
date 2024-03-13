<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wheel_id',
        'title',
        'description',
        'inventory',
        'probability',
        'status'
    ];

    /**
     * Get the discount codes for the slice.
     */
    public function discountCodes(): HasMany
    {
        return $this->hasMany(DiscountCode::class);
    }

    /**
     * Get the not used discount codes for the slice.
     */
    public function discountCodesNotUsed(): HasMany
    {
        return $this->hasMany(DiscountCode::class)->whereNull('user_id');
    }

    /**
     * Get the prizes for the slice.
     */
    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class);
    }
}
