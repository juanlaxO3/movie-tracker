<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="text-center max-w-2xl mx-auto mb-12 animate-fade-in-up">
            <flux:icon name="film" class="size-16 text-amber-500 mx-auto mb-6" />
            <flux:heading size="2xl" class="mb-4">Movie Tracker</flux:heading>
            <flux:subheading class="text-lg mb-8 max-w-lg mx-auto">Keep track of movies you've watched, collect ratings from friends, and discover your next favorite film.</flux:subheading>
            <div class="flex gap-4 justify-center">
                @auth
                    <flux:button href="{{ route('dashboard') }}" wire:navigate variant="primary" class="transition-all duration-200 hover:shadow-lg active:scale-95">Go to Feed</flux:button>
                @else
                    <flux:button href="{{ route('login') }}" wire:navigate variant="primary" class="transition-all duration-200 hover:shadow-lg active:scale-95">Log in</flux:button>
                    <flux:button href="{{ route('register') }}" wire:navigate variant="ghost" class="transition-all duration-200 hover:shadow-md active:scale-95">Register</flux:button>
                @endauth
            </div>
        </div>

        <div class="max-w-5xl mx-auto grid gap-6 sm:grid-cols-3 animate-fade-in-up" style="animation-delay: 200ms">
            <flux:card class="!p-6 text-center group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <flux:icon name="magnifying-glass" class="size-8 text-amber-500 mx-auto mb-3" />
                <flux:heading size="sm" class="mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Search Movies</flux:heading>
                <flux:subheading>Find any movie from the TMDB catalog and add it to your watched list.</flux:subheading>
            </flux:card>
            <flux:card class="!p-6 text-center group hover:shadow-lg transition-all duration-300 hover:-translate-y-1" style="animation-delay: 350ms">
                <flux:icon name="star" class="size-8 text-amber-500 mx-auto mb-3" />
                <flux:heading size="sm" class="mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Rate & Review</flux:heading>
                <flux:subheading>Collect ratings from friends and family. No account needed to rate.</flux:subheading>
            </flux:card>
            <flux:card class="!p-6 text-center group hover:shadow-lg transition-all duration-300 hover:-translate-y-1" style="animation-delay: 500ms">
                <flux:icon name="clock" class="size-8 text-amber-500 mx-auto mb-3" />
                <flux:heading size="sm" class="mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Keep Track</flux:heading>
                <flux:subheading>Build your personal watch history and never forget what you've seen.</flux:subheading>
            </flux:card>
        </div>
    </div>
</x-guest-layout>