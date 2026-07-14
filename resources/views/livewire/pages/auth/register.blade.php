<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <flux:card class="!p-8">
        <div class="text-center mb-8">
            <flux:icon name="film" class="size-12 text-amber-500 mx-auto mb-4" />
            <flux:heading size="lg" class="mb-1">Create an account</flux:heading>
            <flux:subheading>Start tracking your movie journey</flux:subheading>
        </div>

        <form wire:submit="register" class="space-y-5">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" placeholder="Your name" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" placeholder="you@example.com" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model="password" type="password" placeholder="••••••••" />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Confirm Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="••••••••" />
                <flux:error name="password_confirmation" />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full">Create account</flux:button>
        </form>

        <div class="mt-6 text-center">
            <span class="text-sm text-zinc-500 dark:text-zinc-400">Already have an account?</span>
            <a href="{{ route('login') }}" wire:navigate class="text-sm text-amber-600 hover:text-amber-500 dark:text-amber-400 font-medium ml-1 transition">
                Sign in
            </a>
        </div>
    </flux:card>
</div>