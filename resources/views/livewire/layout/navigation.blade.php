<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="w-64 flex-shrink-0 border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 sidebar-gradient flex flex-col h-full">
    <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
        <span class="text-sm font-bold text-zinc-900 dark:text-white">Movie Tracker</span>
    </div>

    <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" wire:navigate
            @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('dashboard'), 'sidebar-link-inactive' => !request()->routeIs('dashboard')])>
            <flux:icon name="bell" class="size-5 shrink-0" />
            <span>Feed</span>
        </a>
        <a href="{{ route('movies.index') }}" wire:navigate
            @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('movies.index'), 'sidebar-link-inactive' => !request()->routeIs('movies.index')])>
            <flux:icon name="film" class="size-5 shrink-0" />
            <span>My Movies</span>
        </a>
        <a href="{{ route('movies.watchlist') }}" wire:navigate
            @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('movies.watchlist'), 'sidebar-link-inactive' => !request()->routeIs('movies.watchlist')])>
            <flux:icon name="bookmark" class="size-5 shrink-0" />
            <span>Watchlist</span>
        </a>
        <a href="{{ route('movies.search') }}" wire:navigate
            @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('movies.search'), 'sidebar-link-inactive' => !request()->routeIs('movies.search')])>
            <flux:icon name="magnifying-glass" class="size-5 shrink-0" />
            <span>Search</span>
        </a>
    </nav>

    <div class="p-3 border-t border-zinc-200 dark:border-zinc-800">
        <div class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 px-4 pb-2">Account</div>

        <a href="{{ route('users.show', auth()->user()) }}" wire:navigate
            @class(['sidebar-link mb-1', 'sidebar-link-active' => request()->routeIs('users.show'), 'sidebar-link-inactive' => !request()->routeIs('users.show')])>
            <flux:avatar name="{{ auth()->user()->name }}" size="xs" />
            <span class="truncate">{{ auth()->user()->name }}</span>
        </a>

        <a href="{{ route('profile') }}" wire:navigate
            @class(['sidebar-link mb-1', 'sidebar-link-active' => request()->routeIs('profile'), 'sidebar-link-inactive' => !request()->routeIs('profile')])>
            <flux:icon name="cog-6-tooth" class="size-5 shrink-0" />
            <span>Settings</span>
        </a>

        <button wire:click="logout" class="sidebar-link sidebar-link-inactive w-full">
            <flux:icon name="arrow-right-start-on-rectangle" class="size-5 shrink-0" />
            <span>Log Out</span>
        </button>
    </div>
</aside>