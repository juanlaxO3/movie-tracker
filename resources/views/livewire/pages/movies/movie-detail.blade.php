<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}" wire:navigate>Feed</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $movie->title }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="grid gap-8 lg:grid-cols-3 mb-12">
        <div class="lg:col-span-1">
            @if ($movie->poster_url)
                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" referrerpolicy="no-referrer" class="w-full rounded-lg shadow-lg">
            @endif
        </div>

        <div class="lg:col-span-2">
            <flux:heading size="xl" class="mb-1">{{ $movie->title }}</flux:heading>
            @if ($movie->original_title && $movie->original_title !== $movie->title)
                <flux:subheading class="mb-4">{{ $movie->original_title }}</flux:subheading>
            @endif

            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                @if ($movie->year)<span>{{ $movie->year }}</span>@endif
                @if ($movie->runtime)<span>{{ $movie->runtime }}</span>@endif
                @if ($movie->language)<span class="uppercase">{{ $movie->language }}</span>@endif
                @if ($movie->country)<span>{{ $movie->country }}</span>@endif
            </div>

            @if ($movie->genres)
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach (explode(', ', $movie->genres) as $genre)
                        <flux:badge>{{ $genre }}</flux:badge>
                    @endforeach
                </div>
            @endif

            @if ($movie->imdb_rating)
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg font-semibold">{{ $movie->imdb_rating }}</span>
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">/10 - {{ $movie->imdb_votes }} TMDB votes</span>
                </div>
            @endif

            @if ($movie->plot)
                <p class="text-zinc-700 dark:text-zinc-300 mb-4 leading-relaxed">{{ $movie->plot }}</p>
            @endif

            @if ($movie->directors)
                <p class="text-sm mb-1"><span class="font-medium text-zinc-800 dark:text-zinc-200">Director:</span> <span class="text-zinc-600 dark:text-zinc-400">{{ $movie->directors }}</span></p>
            @endif

            @if ($movie->actors)
                <p class="text-sm"><span class="font-medium text-zinc-800 dark:text-zinc-200">Cast:</span> <span class="text-zinc-600 dark:text-zinc-400">{{ $movie->actors }}</span></p>
            @endif
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-3 mb-12">
        <div class="lg:col-span-1">
            <div class="space-y-4">
                @if ($communityAvg)
                    <flux:card class="!p-4 text-center">
                        <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 mb-2">Community Rating</p>
                        <div class="flex items-center justify-center gap-2">
                            <flux:icon name="star" class="size-5 text-amber-400" />
                            <span class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $communityAvg }}</span>
                            <span class="text-sm text-zinc-400">/10</span>
                        </div>
                        <p class="text-xs text-zinc-400 mt-1">{{ $communityCount }} {{ Str::plural('rating', $communityCount) }}</p>
                    </flux:card>
                @endif

                @if ($canFavorite)
                    <flux:card class="!p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">Favorites</p>
                            <span class="text-xs text-zinc-400">{{ $favoriteCount }}/3</span>
                        </div>
                        @if ($isFavorite)
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center gap-1.5">
                                    <flux:icon name="star" class="size-4 text-amber-400" />
                                    <span class="text-sm font-medium text-zinc-900 dark:text-white">In your favorites</span>
                                </div>
                                <button wire:click="toggleFavorite" class="text-xs text-red-500 hover:text-red-400 transition-colors">Remove</button>
                            </div>
                        @elseif ($favoriteCount < 3)
                            <button wire:click="toggleFavorite" class="flex items-center gap-1.5 mt-2 w-full justify-center text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-amber-600 dark:hover:text-amber-400 px-3 py-1.5 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <flux:icon name="star" class="size-4" />
                                Add to Favorites
                            </button>
                        @else
                            <p class="text-xs text-zinc-500 mt-2 text-center">Remove a favorite to add this one</p>
                        @endif
                    </flux:card>
                @endif

                @if ($userRating)
                    <flux:card class="!p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium uppercase tracking-wider text-zinc-400">Your Rating</p>
                            <div class="flex items-center gap-1">
                                <button wire:click="editRating" class="text-xs text-amber-600 hover:text-amber-500 transition-colors">Edit</button>
                                <span class="text-xs text-zinc-300">·</span>
                                <button wire:click="confirmDelete" class="text-xs text-red-500 hover:text-red-400 transition-colors">Delete</button>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:icon name="star" class="size-4 text-amber-400 shrink-0" />
                            <span class="text-lg font-bold text-zinc-900 dark:text-white">{{ number_format($userRating->score, 1) }}</span>
                            <span class="text-xs text-zinc-400">/10</span>
                        </div>
                        @if ($userRating->comment)
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1.5 leading-snug italic">&ldquo;{{ $userRating->comment }}&rdquo;</p>
                        @endif
                    </flux:card>
                @else
                    <flux:card class="!p-4 text-center">
                        <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 mb-3">You haven't rated this yet</p>
                        <flux:button wire:click="$set('showAddForm', true)" size="sm" variant="primary">Rate it</flux:button>
                    </flux:card>
                @endif

                @if ($showAddForm || $showEditForm)
                    <flux:card class="!p-4">
                        <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 mb-3">{{ $showEditForm ? 'Edit' : 'Add' }} Your Rating</p>
                        <form wire:submit="saveRating" class="space-y-3">
                            <flux:field>
                                <div class="flex items-center gap-2">
                                    <flux:input wire:model="score" type="number" step="any" min="0.5" max="10" class="!w-20 !text-center !text-base !font-bold tabular-nums" />
                                    <span class="text-sm text-zinc-400">/ 10</span>
                                </div>
                                <flux:error name="score" />
                            </flux:field>

                            <flux:field>
                                <flux:textarea wire:model="comment" placeholder="Your thoughts..." rows="2" class="!text-sm" />
                                <flux:error name="comment" />
                            </flux:field>

                            <div class="flex items-center gap-2">
                                <flux:button type="submit" size="sm" variant="primary">{{ $showEditForm ? 'Save' : 'Submit' }}</flux:button>
                                <flux:button type="button" wire:click="cancelEdit" size="sm" variant="ghost">Cancel</flux:button>
                            </div>
                        </form>
                    </flux:card>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2">
            @if ($communityRatings->isNotEmpty())
                <flux:heading size="lg" class="mb-4">All Ratings</flux:heading>
                <div class="space-y-3">
                    @foreach ($communityRatings as $rating)
                        <flux:card class="!p-3" wire:key="cr-{{ $rating->id }}">
                            <div class="flex items-start gap-3">
                                <flux:avatar name="{{ $rating->user->name }}" size="sm" class="shrink-0 mt-0.5" />
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $rating->user->name }}</span>
                                        <div class="flex items-center gap-1">
                                            <flux:icon name="star" class="size-3.5 text-amber-400" />
                                            <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ number_format($rating->score, 1) }}</span>
                                        </div>
                                    </div>
                                    @if ($rating->comment)
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 leading-snug">&ldquo;{{ $rating->comment }}&rdquo;</p>
                                    @endif
                                    <p class="text-xs text-zinc-400 mt-1">{{ $rating->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <flux:icon name="star" class="size-12 text-zinc-200 dark:text-zinc-800 mx-auto mb-3" />
                    <flux:heading class="mb-1">No ratings yet</flux:heading>
                    <flux:subheading>Be the first to rate this movie.</flux:subheading>
                </div>
            @endif
        </div>
    </div>

    <flux:modal name="confirm-delete" wire:model="showDeleteModal">
        <form wire:submit="deleteRating" class="p-6 space-y-6">
            <flux:heading size="sm">Delete Rating</flux:heading>
            <flux:subheading>Are you sure you want to delete your rating?</flux:subheading>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button type="button" variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Delete</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
