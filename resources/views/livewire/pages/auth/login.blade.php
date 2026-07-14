<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <flux:card class="!p-8">
        <div class="text-center mb-8">
            <flux:icon name="film" class="size-12 text-amber-500 mx-auto mb-4" />
            <flux:heading size="lg" class="mb-1">Welcome back</flux:heading>
            <flux:subheading>Sign in to your Movie Tracker account</flux:subheading>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-5">
            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="form.email" type="email" placeholder="you@example.com" />
                <flux:error name="form.email" />
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model="form.password" type="password" placeholder="••••••••" />
                <flux:error name="form.password" />
            </flux:field>

            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 cursor-pointer">
                    <flux:checkbox wire:model="form.remember" id="remember" />
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-amber-600 hover:text-amber-500 dark:text-amber-400 font-medium transition">
                        Forgot password?
                    </a>
                @endif
            </div>

            <flux:button type="submit" variant="primary" class="w-full">Sign in</flux:button>
        </form>

        <div class="mt-6 text-center">
            <span class="text-sm text-zinc-500 dark:text-zinc-400">Don't have an account?</span>
            <a href="{{ route('register') }}" wire:navigate class="text-sm text-amber-600 hover:text-amber-500 dark:text-amber-400 font-medium ml-1 transition">
                Create one
            </a>
        </div>
    </flux:card>
</div>