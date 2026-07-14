<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @fluxAppearance
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-50 dark:bg-zinc-950">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
            <div x-cloak
                 :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                 class="fixed inset-y-0 left-0 z-30 w-64 -translate-x-full transition-transform duration-300 lg:static lg:translate-x-0 lg:z-auto">
                <livewire:layout.navigation />
            </div>

            <div x-cloak x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

            <button x-cloak x-show="!sidebarOpen"
                     @@click="sidebarOpen = !sidebarOpen"
                    class="fixed top-3 left-3 z-30 lg:hidden flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-md hover:text-zinc-900 dark:hover:text-white transition-colors">
                <flux:icon name="bars-3" class="size-5" />
                <span>Menu</span>
            </button>

            <main class="flex-1 overflow-y-auto min-w-0">
                <flux:main>
                    @if (isset($header))
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
                            <flux:heading size="lg">{{ $header }}</flux:heading>
                        </div>
                    @endif

                    {{ $slot }}
                </flux:main>
            </main>
        </div>
        @fluxScripts
    </body>
</html>