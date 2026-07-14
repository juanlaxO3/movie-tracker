<?php

namespace App\Livewire\Pages;

use App\Models\UserMovie;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityFeed extends Component
{
    use WithPagination;

    public function render()
    {
        $activities = UserMovie::with(['user', 'movie', 'ratings.user'])
            ->where('show_in_feed', true)
            ->latest()
            ->paginate(20);

        return view('livewire.pages.activity-feed', [
            'activities' => $activities,
        ])->layout('layouts.app', ['header' => 'Activity Feed']);
    }
}
