<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'user_movie_id',
        'score',
        'comment',
    ];

    public function userMovie(): BelongsTo
    {
        return $this->belongsTo(UserMovie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
