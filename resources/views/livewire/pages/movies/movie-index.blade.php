<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-end mb-8 animate-fade-in">
        <div class="flex items-center gap-3 flex-wrap">
            <flux:select wire:model.live="sortBy" class="!w-36 sm:!w-44">
                <option value="rating_desc">Highest rated</option>
                <option value="latest">Most recent</option>
            </flux:select>
            <flux:button href="{{ route('movies.search') }}" wire:navigate icon="plus" class="transition-all duration-200 hover:shadow-md active:scale-95 shrink-0">Add Movie</flux:button>
        </div>
    </div>

    @if ($userMovies->isEmpty())
        <div class="text-center py-24 animate-fade-in">
            <flux:icon name="film" class="size-20 text-zinc-200 dark:text-zinc-800 mx-auto mb-4" />
            <flux:heading class="mb-2">No movies yet</flux:heading>
            <flux:subheading class="mb-6">Search for movies to add to your watched list.</flux:subheading>
            <flux:button href="{{ route('movies.search') }}" wire:navigate variant="primary" class="transition-all duration-200 hover:shadow-md active:scale-95">Search Movies</flux:button>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($userMovies as $userMovie)
                <div wire:key="um-{{ $userMovie->id }}" class="flex items-stretch gap-4 rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 group transition-all duration-300 hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800 animate-fade-in-up">
                    <a href="{{ route('movies.show', $userMovie) }}" wire:navigate class="shrink-0">
                        @if ($userMovie->movie->poster_url)
                            <img src="{{ $userMovie->movie->poster_url }}" alt="{{ $userMovie->movie->title }}" referrerpolicy="no-referrer" loading="lazy" class="w-16 sm:w-20 h-24 sm:h-28 object-cover transition-all duration-300 group-hover:scale-105">
                        @else
                            <div class="w-16 sm:w-20 h-24 sm:h-28 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                <flux:icon name="film" class="size-5 sm:size-6 text-zinc-400" />
                            </div>
                        @endif
                    </a>

                    <div class="flex-1 flex items-center justify-between py-3 pr-4 min-w-0">
                        <div class="min-w-0">
                            <a href="{{ route('movies.show', $userMovie) }}" wire:navigate>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200">{{ $userMovie->movie->title }}</p>
                            </a>

                            <div class="flex items-center gap-3 mt-1.5">
                                @php
                                    $myScore = $userMovie->ratings->avg('score');
                                    $communityScore = $userMovie->movie->allRatings->avg('score');
                                @endphp
                                <div class="flex items-center gap-1">
                                    <flux:icon name="star" class="size-3.5 text-amber-400 shrink-0" />
                                    <span class="text-sm font-semibold">{{ $communityScore ? number_format($communityScore, 1) : '-' }}</span>
                                    <span class="text-xs text-zinc-400">avg</span>
                                </div>
                                <span class="text-xs text-zinc-300">·</span>
                                @if ($myScore)
                                    <span class="text-xs text-zinc-500">you: {{ number_format($myScore, 1) }}</span>
                                @else
                                    <span class="text-xs text-zinc-400">Not rated</span>
                                @endif
                                @if ($userMovie->movie->year)
                                    <span class="text-xs text-zinc-500">{{ $userMovie->movie->year }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 ml-4">
                            <a href="{{ route('movies.show', $userMovie) }}" wire:navigate class="text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:text-amber-600 dark:hover:text-amber-400 px-3 py-1.5 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-150">View</a>
                            <button wire:click="confirmRemove({{ $userMovie->id }})" class="text-xs font-medium text-red-500 hover:text-red-600 px-3 py-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150">Remove</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <flux:modal name="confirm-remove" wire:model="showRemoveModal">
            <form wire:submit="removeConfirmed" class="p-6 space-y-6">
                <flux:heading size="sm">Remove movie?</flux:heading>
                <flux:subheading>This will remove this movie from your watched list. Ratings will also be deleted.</flux:subheading>

                <div class="flex justify-end gap-3">
                    <flux:modal.close>
                        <flux:button type="button" variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger">Remove</flux:button>
                </div>
            </form>
        </flux:modal>
    @endif
</div>