<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCode extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'wheel_id',
        'slice_id',
        'user_id',
        'code'
    ];

    /**
     * Get the user requirement that owns the discount code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
