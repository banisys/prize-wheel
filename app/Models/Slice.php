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
        'priority',
    ];

    /**
     * Get the discount codes for the slice.
     */
    public function discountCodes(): HasMany
    {
        return $this->hasMany(DiscountCode::class);
    }

    /**
     * Get the discount codes for the slice.
     */
    public function discountCodesCount()
    {
        return $this->hasMany(DiscountCode::class)->count();
    }
}
