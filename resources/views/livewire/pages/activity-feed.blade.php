<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-10 animate-fade-in">
        <flux:heading size="xl" class="mb-2">Activity Feed</flux:heading>
        <flux:subheading class="max-w-md mx-auto">See what everyone is watching and rating.</flux:subheading>
    </div>

    @if ($activities->isEmpty())
        <div class="text-center py-24 animate-fade-in">
            <flux:icon name="film" class="size-20 text-zinc-200 dark:text-zinc-800 mx-auto mb-4" />
            <flux:heading class="mb-2">No activity yet</flux:heading>
            <flux:subheading class="mb-6">When users add movies or ratings, they'll appear here.</flux:subheading>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($activities as $activity)
                @php
                    $userRating = $activity->ratings->firstWhere('user_id', $activity->user_id);
                @endphp
                <div wire:key="activity-{{ $activity->id }}" class="rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 group transition-all duration-300 hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800">
                    <div class="grid grid-cols-[60px_1fr] sm:grid-cols-[80px_1fr]">
                        <a href="{{ route('movies.show', $activity) }}" wire:navigate>
                            @if ($activity->movie->poster_url)
                                <div class="h-full min-h-[84px] sm:min-h-[112px] bg-cover bg-center rounded-l-xl" style="background-image: url('{{ $activity->movie->poster_url }}')"></div>
                            @else
                                <div class="h-full min-h-[84px] sm:min-h-[112px] bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center rounded-l-xl">
                                    <flux:icon name="film" class="size-6 text-zinc-400" />
                                </div>
                            @endif
                        </a>

                        <div class="flex flex-col justify-center py-3 pr-4 pl-4 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <a href="{{ route('users.show', $activity->user) }}" wire:navigate class="text-sm font-semibold text-amber-600 dark:text-amber-400 hover:underline">{{ $activity->user->name }}</a>
                                @if ($activity->list_type === 'watchlist')
                                    <span class="text-xs text-zinc-400">wants to watch</span>
                                @elseif ($userRating)
                                    <span class="text-xs text-zinc-400">rated</span>
                                @else
                                    <span class="text-xs text-zinc-400">watched</span>
                                @endif
                            </div>

                            <a href="{{ route('movies.show', $activity) }}" wire:navigate>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                                    {{ $activity->movie->title }}
                                </p>
                            </a>

                            @if ($activity->movie->year)
                                <span class="text-xs text-zinc-500 mt-0.5">{{ $activity->movie->year }}</span>
                            @endif

                            @if ($userRating)
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex items-center gap-1">
                                        <flux:icon name="star" class="size-4 text-amber-400 shrink-0" />
                                        <span class="text-sm font-bold">{{ number_format($userRating->score, 1) }}</span>
                                    </div>
                                    @if ($userRating->comment)
                                        <span class="text-xs text-zinc-400 truncate">&ldquo;{{ $userRating->comment }}&rdquo;</span>
                                    @endif
                                </div>
                            @endif

                            <span class="text-xs text-zinc-400 mt-1.5">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $activities->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
