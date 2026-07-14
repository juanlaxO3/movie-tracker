<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfile extends Component
{
    public User $user;

    public bool $editingBio = false;

    public ?string $bio = null;

    public function mount(User $user): void
    {
        $this->user = $user->loadCount(['watchedMovies', 'watchlistMovies']);
        $this->bio = $user->bio;
    }

    public function toggleEditBio(): void
    {
        $this->editingBio = !$this->editingBio;
        $this->bio = $this->user->bio;
    }

    public function saveBio(): void
    {
        $this->validate(['bio' => 'nullable|string|max:1000']);

        $this->user->update(['bio' => $this->bio]);
        $this->editingBio = false;
    }

    public function render()
    {
        $favorites = $this->user->favoriteMovies()
            ->with('movie')
            ->latest()
            ->take(3)
            ->get();

        $ratedMovies = $this->user->userMovies()
            ->where('list_type', 'watched')
            ->whereHas('ratings')
            ->with(['movie', 'ratings' => fn($q) => $q->where('user_id', $this->user->id)])
            ->latest()
            ->get()
            ->sortByDesc(fn($um) => $um->ratings->first()?->score);

        $watchlist = $this->user->watchlistMovies()
            ->with('movie')
            ->latest()
            ->get();

        return view('livewire.pages.users.user-profile', [
            'favorites' => $favorites,
            'ratedMovies' => $ratedMovies,
            'watchlist' => $watchlist,
        ])->layout('layouts.app', ['header' => $this->user->name]);
    }
}
