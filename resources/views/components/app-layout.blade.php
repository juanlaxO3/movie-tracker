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
        <div class="flex h-screen overflow-hidden">
            <livewire:layout.navigation />

            <main class="flex-1 overflow-y-auto">
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