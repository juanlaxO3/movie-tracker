<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserMovie extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'watched_at',
        'list_type',
        'show_in_feed',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'show_in_feed' => 'boolean',
            'is_favorite' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
