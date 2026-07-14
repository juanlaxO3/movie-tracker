<?php

use App\Livewire\Pages\ActivityFeed;
use App\Livewire\Pages\Movies\MovieDetail;
use App\Livewire\Pages\Movies\MovieIndex;
use App\Livewire\Pages\Movies\SearchMovies;
use App\Livewire\Pages\Movies\Watchlist;
use App\Livewire\Pages\Users\UserProfile;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ActivityFeed::class)->name('dashboard');
    Route::get('/movies', MovieIndex::class)->name('movies.index');
    Route::get('/movies/search', SearchMovies::class)->name('movies.search');
    Route::get('/movies/watchlist', Watchlist::class)->name('movies.watchlist');
    Route::get('/movies/{userMovie}', MovieDetail::class)->name('movies.show');
    Route::get('/users/{user}', UserProfile::class)->name('users.show');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', function (App\Livewire\Actions\Logout $logout) {
    $logout();
    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
