<?php

namespace App\Livewire\Pages\Movies;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Watchlist extends Component
{
    public ?int $removingMovieId = null;

    public bool $showRemoveModal = false;

    public function confirmRemove(int $userMovieId): void
    {
        $this->removingMovieId = $userMovieId;
        $this->showRemoveModal = true;
    }

    public function removeConfirmed(): void
    {
        $userMovie = Auth::user()->watchlistMovies()->findOrFail($this->removingMovieId);
        $userMovie->delete();
        $this->removingMovieId = null;
        $this->showRemoveModal = false;
    }

    public function moveToWatched(int $userMovieId): void
    {
        $userMovie = Auth::user()->watchlistMovies()->findOrFail($userMovieId);
        $userMovie->update([
            'list_type' => 'watched',
            'watched_at' => now(),
        ]);
    }

    public function render()
    {
        $userMovies = Auth::user()
            ->watchlistMovies()
            ->with('movie')
            ->latest()
            ->get();

        return view('livewire.pages.movies.watchlist', [
            'userMovies' => $userMovies,
        ])->layout('layouts.app', ['header' => 'Watchlist']);
    }
}