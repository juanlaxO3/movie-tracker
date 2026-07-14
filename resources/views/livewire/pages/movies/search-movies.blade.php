<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-10 animate-fade-in">
        <flux:icon name="magnifying-glass" class="size-10 text-amber-500 mx-auto mb-3" />
        <flux:heading size="xl" class="mb-2">Search Movies</flux:heading>
        <flux:subheading class="max-w-md mx-auto">Find any movie from the TMDB catalog and add it to your list.</flux:subheading>
    </div>

    <div class="max-w-2xl mx-auto mb-10 animate-fade-in">
        <flux:field>
            <div class="relative">
                <flux:input
                    wire:model.live.debounce.500ms="query"
                    placeholder="Search for a movie..."
                    class="!pl-10 !py-3 !text-base !rounded-xl !shadow-sm"
                />
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-zinc-500 pointer-events-none">
                    <flux:icon name="magnifying-glass" class="size-5" />
                </div>
            </div>
            <flux:error name="query" />
        </flux:field>
    </div>

    @if ($searching)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 max-w-7xl mx-auto">
            @foreach (range(1, 8) as $i)
                <div class="rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 animate-pulse-soft" style="animation-delay: {{ ($i - 1) * 80 }}ms">
                    <div class="aspect-[2/3] skeleton-box bg-zinc-200 dark:bg-zinc-800"></div>
                    <div class="p-4 space-y-2">
                        <div class="h-4 w-3/4 rounded skeleton-box bg-zinc-200 dark:bg-zinc-800"></div>
                        <div class="h-3 w-1/4 rounded skeleton-box bg-zinc-200 dark:bg-zinc-800"></div>
                        <div class="h-9 w-full rounded-lg skeleton-box bg-zinc-200 dark:bg-zinc-800 mt-3"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif ($searchError)
        <div class="text-center py-20 animate-fade-in">
            <flux:icon name="exclamation-triangle" class="size-16 text-amber-400 mx-auto mb-4" />
            <flux:heading class="mb-2">Search unavailable</flux:heading>
            <flux:subheading>{{ $searchError }}</flux:subheading>
        </div>
    @elseif (count($results) > 0)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 max-w-7xl mx-auto">
            @foreach ($results as $item)
                <div wire:key="result-{{ $item['tmdb_id'] }}" class="search-card rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-amber-200 dark:hover:border-amber-800">
                    @if ($item['poster_url'])
                        <div class="relative overflow-hidden">
                            <img src="{{ $item['poster_url'] }}" alt="{{ $item['title'] }}" referrerpolicy="no-referrer" loading="lazy" class="w-full aspect-[2/3] object-cover transition-all duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    @else
                        <div class="w-full aspect-[2/3] bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center transition-colors duration-300 group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700">
                            <flux:icon name="film" class="size-12 text-zinc-400" />
                        </div>
                    @endif
                    <div class="p-4">
                        <flux:heading size="sm" class="mb-1 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200">{{ $item['title'] }}</flux:heading>
                        @if ($item['year'])
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">{{ $item['year'] }}</p>
                        @endif
                        @php
                            $existingList = $userMoviesByTmdb[$item['tmdb_id']] ?? null;
                        @endphp
                        @if ($existingList === 'watched')
                            <span class="inline-flex items-center gap-1.5 w-full justify-center text-xs font-medium text-zinc-400 dark:text-zinc-500 py-2">
                                <flux:icon name="check" class="size-3.5" />In My Movies
                            </span>
                        @else
                            <div class="flex gap-2">
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    wire:click="confirmAdd({{ $item['tmdb_id'] }}, 'watched')"
                                    class="flex-1 transition-all duration-200 hover:shadow-md active:scale-95"
                                >
                                    Watched
                                </flux:button>
                                @if ($existingList !== 'watchlist')
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        wire:click="confirmAdd({{ $item['tmdb_id'] }}, 'watchlist')"
                                        class="flex-1 transition-all duration-200 hover:shadow-md active:scale-95"
                                    >
                                        Watchlist
                                    </flux:button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif ($query && !$searching)
        <div class="text-center py-20 animate-fade-in">
            <flux:icon name="magnifying-glass" class="size-16 text-zinc-300 dark:text-zinc-600 mx-auto mb-4" />
            <flux:heading class="mb-2">No results found</flux:heading>
            <flux:subheading>No movies found for "{{ $query }}". Try a different search term.</flux:subheading>
        </div>
    @else
        <div class="text-center py-20 animate-fade-in">
            <flux:icon name="film" class="size-20 text-zinc-200 dark:text-zinc-800 mx-auto mb-4" />
            <flux:heading class="mb-2">Ready to explore?</flux:heading>
            <flux:subheading>Type at least 2 characters above to start searching.</flux:subheading>
        </div>
    @endif

    <flux:modal name="confirm-add" wire:model="showConfirmModal">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <flux:icon name="film" class="size-6 text-amber-500" />
                <flux:heading size="sm">{{ $confirmAction === 'watchlist' ? 'Add to Watchlist' : 'Add to Watched' }}</flux:heading>
            </div>
            <flux:subheading class="mb-6">Add "{{ $pendingTitle }}" to your {{ $confirmAction === 'watchlist' ? 'watchlist' : 'watched list' }}?</flux:subheading>

            @if ($confirmAction === 'watchlist')
                <label class="flex items-center gap-3 mb-6 cursor-pointer">
                    <flux:checkbox wire:model="showInFeed" />
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Show in activity feed</span>
                </label>
            @endif

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button type="button" variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="addMovie" variant="primary">Yes, add it</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="success-add" wire:model="showSuccessModal">
        <div class="p-6 text-center">
            <flux:icon name="{{ $successAction === 'watchlist' ? 'bookmark' : 'star' }}" class="size-12 text-amber-500 mx-auto mb-4" />
            <flux:heading size="sm" class="mb-2">Movie Added!</flux:heading>
            <flux:subheading class="mb-6">"{{ $successTitle }}" has been added to your {{ $successAction === 'watchlist' ? 'watchlist' : 'watched list' }}.</flux:subheading>

            <div class="flex justify-center gap-3">
                <flux:button wire:click="goToTarget" variant="primary">{{ $successAction === 'watchlist' ? 'Go to Watchlist' : 'Go to My Movies' }}</flux:button>
                <flux:button wire:click="$set('showSuccessModal', false)" variant="ghost">Keep searching</flux:button>
            </div>
        </div>
    </flux:modal>
</div>