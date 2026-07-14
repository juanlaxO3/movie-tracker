<?php

namespace App\Livewire\Pages\Movies;

use App\Models\Movie;
use App\Services\ImdbApiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SearchMovies extends Component
{
    public string $query = '';

    public array $results = [];

    public bool $searching = false;

    public ?string $searchError = null;

    public string $listType = 'watched';

    public ?int $pendingTmdbId = null;

    public string $pendingTitle = '';

    public bool $showConfirmModal = false;

    public bool $showSuccessModal = false;

    public string $successTitle = '';

    public string $confirmAction = 'watched';

    public string $successAction = 'watched';

    public bool $showInFeed = true;

    public function updatedQuery($value): void
    {
        if (strlen($value) < 2) {
            $this->results = [];
            $this->searching = false;
            $this->searchError = null;
            return;
        }

        $this->searching = true;
        $this->searchError = null;

        try {
            $this->results = app(ImdbApiService::class)->search($value);
        } catch (\Exception $e) {
            $this->results = [];
            $this->searchError = 'Could not connect to the movie database. Check your TMDB API key or try again.';
        }

        $this->searching = false;
    }

    public function confirmAdd(int $tmdbId, string $action): void
    {
        $existingList = $this->getUserMoviesByTmdb()[$tmdbId] ?? null;

        if ($existingList === 'watched') {
            return;
        }

        if ($existingList === 'watchlist' && $action === 'watchlist') {
            return;
        }

        $this->pendingTmdbId = $tmdbId;
        $this->confirmAction = $action;
        $this->listType = $action;
        $this->pendingTitle = collect($this->results)->firstWhere('tmdb_id', $tmdbId)['title'] ?? '';
        $this->showInFeed = $action !== 'watchlist' || $this->showInFeed;
        $this->showConfirmModal = true;
    }

    public function addMovie(): void
    {
        if (!$this->pendingTmdbId) {
            return;
        }

        try {
            $details = app(ImdbApiService::class)->titleDetails($this->pendingTmdbId);
        } catch (\Exception $e) {
            $this->showConfirmModal = false;
            $this->searchError = 'Could not fetch movie details. Check your TMDB API key or try again.';
            return;
        }

        $movie = Movie::where('tmdb_id', $details['tmdb_id'])->first();

        if (!$movie && $details['imdb_id']) {
            $movie = Movie::where('imdb_id', $details['imdb_id'])->first();
            if ($movie) {
                $movie->update($details);
            }
        }

        if (!$movie) {
            $movie = Movie::create($details);
        }

        $user = Auth::user();
        $existing = $user->userMovies()->where('movie_id', $movie->id)->first();

        if ($existing) {
            if ($existing->list_type === 'watched') {
                return;
            }

            if ($this->listType === 'watched') {
                $existing->update([
                    'list_type' => 'watched',
                    'watched_at' => now(),
                ]);
            } else {
                return;
            }
        } else {
            $user->userMovies()->create([
                'movie_id' => $movie->id,
                'list_type' => $this->listType,
                'watched_at' => $this->listType === 'watched' ? now() : null,
                'show_in_feed' => $this->showInFeed,
            ]);
        }

        $this->successTitle = $details['title'];
        $this->successAction = $this->listType;
        $this->showConfirmModal = false;
        $this->pendingTmdbId = null;
        $this->showInFeed = true;
        $this->showSuccessModal = true;
    }

    public function goToTarget(): void
    {
        $this->showSuccessModal = false;

        if ($this->successAction === 'watchlist') {
            $this->redirectRoute('movies.watchlist', navigate: true);
        } else {
            $this->redirectRoute('movies.index', navigate: true);
        }
    }

    public function getUserMoviesByTmdb(): array
    {
        return Auth::user()->userMovies()
            ->with('movie')
            ->get()
            ->mapWithKeys(fn ($um) => [$um->movie->tmdb_id => $um->list_type])
            ->all();
    }

    public function render()
    {
        return view('livewire.pages.movies.search-movies', [
            'userMoviesByTmdb' => $this->getUserMoviesByTmdb(),
        ])
            ->layout('layouts.app', ['header' => 'Search Movies']);
    }
}