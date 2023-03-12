<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Url extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'code',
        'hits',
        'user_id',
    ];

    /**
     * Get the user that owns the url.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
