<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'imdb_id',
        'title',
        'original_title',
        'year',
        'release_date',
        'runtime',
        'plot',
        'poster_url',
        'backdrop_url',
        'imdb_rating',
        'imdb_votes',
        'genres',
        'directors',
        'actors',
        'language',
        'country',
    ];

    public function userMovies(): HasMany
    {
        return $this->hasMany(UserMovie::class);
    }

    public function allRatings(): HasManyThrough
    {
        return $this->hasManyThrough(Rating::class, UserMovie::class, 'movie_id', 'user_movie_id');
    }
}
