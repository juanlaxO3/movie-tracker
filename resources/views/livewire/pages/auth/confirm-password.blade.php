<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <flux:card class="!p-8">
        <div class="text-center mb-8">
            <flux:icon name="film" class="size-12 text-amber-500 mx-auto mb-4" />
            <flux:heading size="lg" class="mb-1">Confirm password</flux:heading>
            <flux:subheading>This is a secure area. Please confirm your password before continuing.</flux:subheading>
        </div>

        <form wire:submit="confirmPassword" class="space-y-5">
            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model="password" type="password" placeholder="••••••••" />
                <flux:error name="password" />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full">Confirm</flux:button>
        </form>
    </flux:card>
</div>