<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prize extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'wheel_id',
        'title',
        'token',
        'description',
        'probability',
        'old'
    ];

    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'];
    }

    /**
     * Get the user requirement that owns the prize.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
