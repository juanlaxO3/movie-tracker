<?php

namespace App\Livewire\Pages\Movies;

use App\Models\Rating;
use App\Models\UserMovie;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MovieDetail extends Component
{
    public UserMovie $userMovie;

    public float $score = 5;

    public string $comment = '';

    public bool $showAddForm = false;

    public bool $showDeleteModal = false;

    public bool $showEditForm = false;

    public function mount(UserMovie $userMovie): void
    {
        if ($userMovie->user_id !== Auth::id()) {
            $own = Auth::user()->userMovies()
                ->where('movie_id', $userMovie->movie_id)
                ->first();

            if ($own) {
                $userMovie = $own;
            }
        }

        $this->userMovie = $userMovie->loadMissing(['movie', 'ratings.user']);
    }

    public function editRating(): void
    {
        $rating = $this->userMovie->ratings()->where('user_id', Auth::id())->firstOrFail();

        $this->score = (float) $rating->score;
        $this->comment = $rating->comment ?? '';
        $this->showEditForm = true;
        $this->showAddForm = false;
    }

    public function cancelEdit(): void
    {
        $this->showEditForm = false;
        $this->showAddForm = false;
        $this->score = 5;
        $this->comment = '';
    }

    public function saveRating(): void
    {
        $this->validate([
            'score' => 'required|numeric|min:0.5|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($this->userMovie->user_id !== Auth::id()) {
            $userMovie = Auth::user()->userMovies()
                ->where('movie_id', $this->userMovie->movie_id)
                ->first();

            if (!$userMovie) {
                $userMovie = Auth::user()->userMovies()->create([
                    'movie_id' => $this->userMovie->movie_id,
                    'list_type' => 'watched',
                    'watched_at' => now(),
                    'show_in_feed' => true,
                ]);
            } elseif ($userMovie->list_type !== 'watched') {
                $userMovie->update([
                    'list_type' => 'watched',
                    'watched_at' => now(),
                    'show_in_feed' => true,
                ]);
            }

            $this->userMovie = $userMovie;
        } elseif ($this->userMovie->list_type !== 'watched') {
            $this->userMovie->update([
                'list_type' => 'watched',
                'watched_at' => now(),
                'show_in_feed' => true,
            ]);
        }

        $this->userMovie->created_at = now();
        $this->userMovie->save();

        $this->userMovie->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['score' => $this->score, 'comment' => $this->comment]
        );

        $this->userMovie->loadMissing(['ratings.user']);
        $this->cancelEdit();
    }

    public function confirmDelete(): void
    {
        $this->showDeleteModal = true;
    }

    public function deleteRating(): void
    {
        $this->userMovie->ratings()->where('user_id', Auth::id())->delete();
        $this->showDeleteModal = false;
        $this->userMovie->loadMissing(['ratings.user']);
    }

    public function toggleFavorite(): void
    {
        if ($this->userMovie->user_id !== Auth::id()) {
            return;
        }

        if ($this->userMovie->is_favorite) {
            $this->userMovie->update(['is_favorite' => false]);
        } else {
            $favoriteCount = Auth::user()->favoriteMovies()->count();
            if ($favoriteCount >= 3) {
                $this->dispatch('favorite-limit');
                return;
            }
            $this->userMovie->update(['is_favorite' => true]);
        }

        $this->userMovie->loadMissing(['movie', 'ratings.user']);
    }

    public function render()
    {
        $movie = $this->userMovie->movie;
        $userRating = $this->userMovie->ratings->firstWhere('user_id', Auth::id());

        $communityRatings = Rating::whereHas('userMovie', fn($q) => $q->where('movie_id', $movie->id))
            ->with('user')
            ->latest()
            ->get();

        $communityAvg = $communityRatings->avg('score');

        $isOwnMovie = $this->userMovie->user_id === Auth::id();
        $isFavorite = $this->userMovie->is_favorite;
        $favoriteCount = Auth::user()->favoriteMovies()->count();
        $canFavorite = $isOwnMovie && $this->userMovie->list_type === 'watched';

        return view('livewire.pages.movies.movie-detail', [
            'movie' => $movie,
            'userRating' => $userRating,
            'communityRatings' => $communityRatings,
            'communityAvg' => $communityAvg ? number_format($communityAvg, 1) : null,
            'communityCount' => $communityRatings->count(),
            'isFavorite' => $isFavorite,
            'favoriteCount' => $favoriteCount,
            'canFavorite' => $canFavorite,
        ])->layout('layouts.app', ['header' => $movie->title]);
    }
}
