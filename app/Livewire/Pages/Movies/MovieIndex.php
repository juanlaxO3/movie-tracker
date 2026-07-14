<?php

namespace App\Livewire\Pages\Movies;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MovieIndex extends Component
{
    public ?int $removingMovieId = null;

    public bool $showRemoveModal = false;

    public string $sortBy = 'rating_desc';

    public function confirmRemove(int $userMovieId): void
    {
        $this->removingMovieId = $userMovieId;
        $this->showRemoveModal = true;
    }

    public function removeConfirmed(): void
    {
        $userMovie = Auth::user()->userMovies()->findOrFail($this->removingMovieId);
        $userMovie->delete();
        $this->removingMovieId = null;
        $this->showRemoveModal = false;
    }

    public function render()
    {
        $userMovies = Auth::user()
            ->watchedMovies()
            ->with(['movie', 'ratings'])
            ->latest()
            ->get();

        $userMovies->load(['movie.allRatings']);

        if ($this->sortBy === 'rating_desc') {
            $userMovies = $userMovies->sortByDesc(fn($um) => $um->movie->allRatings->avg('score') ?? 0);
        }

        return view('livewire.pages.movies.movie-index', [
            'userMovies' => $userMovies,
        ])->layout('layouts.app', ['header' => 'Movies']);
    }
}
