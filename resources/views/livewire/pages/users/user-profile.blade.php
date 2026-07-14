<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-10 animate-fade-in">
        <div class="flex items-start gap-4 mb-2">
            <flux:avatar name="{{ $user->name }}" size="lg" />
            <div class="flex-1 min-w-0">
                <flux:heading size="xl">{{ $user->name }}</flux:heading>

                @if (Auth::id() === $user->id && $editingBio)
                    <div class="mt-2 space-y-2">
                        <flux:textarea wire:model="bio" placeholder="Write something about yourself..." rows="2" class="!text-sm" />
                        <flux:error name="bio" />
                        <div class="flex items-center gap-2">
                            <flux:button wire:click="saveBio" size="sm" variant="primary">Save</flux:button>
                            <flux:button wire:click="toggleEditBio" size="sm" variant="ghost">Cancel</flux:button>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 mt-1">
                        @if ($user->bio)
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 max-w-lg">{{ $user->bio }}</p>
                        @elseif (Auth::id() === $user->id)
                            <p class="text-sm text-zinc-400 italic">No bio yet.</p>
                        @endif
                        @if (Auth::id() === $user->id)
                            <button wire:click="toggleEditBio" class="text-xs text-zinc-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors shrink-0">
                                <flux:icon name="pencil" class="size-3.5" />
                            </button>
                        @endif
                    </div>
                @endif

                <p class="text-xs text-zinc-400 mt-2">
                    {{ $user->watched_movies_count }} watched ·
                    {{ $user->watchlist_movies_count }} to watch
                </p>
            </div>
        </div>
    </div>

    @if ($favorites->isNotEmpty())
        <div class="mb-12 animate-fade-in-up">
            <flux:heading size="lg" class="mb-1">Top Favorites</flux:heading>
            <flux:subheading class="mb-6">{{ $user->name }}'s favorite movies.</flux:subheading>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                @foreach ($favorites as $fav)
                    <a href="{{ route('movies.show', $fav) }}" wire:navigate class="group block rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 transition-all duration-300 hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800">
                        @if ($fav->movie->poster_url)
                            <div class="aspect-[2/3] bg-cover bg-center" style="background-image: url('{{ $fav->movie->poster_url }}')"></div>
                        @else
                            <div class="aspect-[2/3] bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                <flux:icon name="film" class="size-6 text-zinc-400" />
                            </div>
                        @endif
                        <div class="p-2">
                            <p class="text-xs font-medium text-zinc-900 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $fav->movie->title }}</p>
                            @if ($fav->movie->year)
                                <p class="text-xs text-zinc-500 mt-0.5">{{ $fav->movie->year }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if (Auth::id() !== $user->id)
        @if ($ratedMovies->isNotEmpty())
            <div class="mb-12 animate-fade-in-up">
                <flux:heading size="lg" class="mb-1">Rated Movies</flux:heading>
                <flux:subheading class="mb-6">Movies {{ $user->name }} has rated.</flux:subheading>
                <div class="space-y-4">
                    @foreach ($ratedMovies as $um)
                        <div wire:key="rated-{{ $um->id }}" class="flex items-stretch gap-4 rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 group transition-all duration-300 hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800">
                        <a href="{{ route('movies.show', $um) }}" wire:navigate class="shrink-0">
                            @if ($um->movie->poster_url)
                                <img src="{{ $um->movie->poster_url }}" alt="{{ $um->movie->title }}" referrerpolicy="no-referrer" loading="lazy" class="w-16 sm:w-20 h-24 sm:h-28 object-cover transition-all duration-300 group-hover:scale-105">
                            @else
                                <div class="w-16 sm:w-20 h-24 sm:h-28 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <flux:icon name="film" class="size-5 sm:size-6 text-zinc-400" />
                                </div>
                            @endif
                        </a>
                            <div class="flex-1 flex items-center justify-between py-3 pr-4 min-w-0">
                                <div class="min-w-0">
                                    <a href="{{ route('movies.show', $um) }}" wire:navigate>
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $um->movie->title }}</p>
                                    </a>
                                    <div class="flex items-center gap-1 mt-1.5">
                                        <flux:icon name="star" class="size-4 text-amber-400 shrink-0" />
                                        @php $score = $um->ratings->first()?->score; @endphp
                                        <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ number_format($score, 1) }}</span>
                                        <span class="text-xs text-zinc-400">/10</span>
                                        @if ($um->ratings->first()?->comment)
                                            <span class="text-xs text-zinc-400 ml-2 truncate">&ldquo;{{ $um->ratings->first()->comment }}&rdquo;</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('movies.show', $um) }}" wire:navigate class="text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:text-amber-600 dark:hover:text-amber-400 px-3 py-1.5 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors shrink-0 ml-4">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($watchlist->isNotEmpty())
            <div class="mb-12 animate-fade-in-up">
                <flux:heading size="lg" class="mb-1">Watchlist</flux:heading>
                <flux:subheading class="mb-6">Movies {{ $user->name }} wants to watch.</flux:subheading>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @foreach ($watchlist as $um)
                        <div wire:key="wl-{{ $um->id }}" class="group block rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 transition-all duration-300 hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800">
                            <a href="{{ route('movies.show', $um) }}" wire:navigate>
                                @if ($um->movie->poster_url)
                                    <div class="aspect-[2/3] bg-cover bg-center" style="background-image: url('{{ $um->movie->poster_url }}')"></div>
                                @else
                                    <div class="aspect-[2/3] bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <flux:icon name="film" class="size-8 text-zinc-400" />
                                    </div>
                                @endif
                            </a>
                            <div class="p-2.5">
                                <p class="text-xs font-medium text-zinc-900 dark:text-white truncate">{{ $um->movie->title }}</p>
                                @if ($um->movie->year)
                                    <p class="text-xs text-zinc-500 mt-0.5">{{ $um->movie->year }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    @if ($favorites->isEmpty() && $ratedMovies->isEmpty() && $watchlist->isEmpty())
        <div class="text-center py-24 animate-fade-in">
            <flux:icon name="user" class="size-20 text-zinc-200 dark:text-zinc-800 mx-auto mb-4" />
            <flux:heading class="mb-2">No activity yet</flux:heading>
            <flux:subheading class="mb-6">{{ $user->name }} hasn't added any movies yet.</flux:subheading>
        </div>
    @endif
</div>
