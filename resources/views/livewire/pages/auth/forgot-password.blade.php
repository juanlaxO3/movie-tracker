<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <flux:card class="!p-8">
        <div class="text-center mb-8">
            <flux:icon name="magnifying-glass" class="size-12 text-amber-500 mx-auto mb-4" />
            <flux:heading size="lg" class="mb-1">Forgot password?</flux:heading>
            <flux:subheading>No problem. Enter your email and we'll send you a reset link.</flux:subheading>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-5">
            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" placeholder="you@example.com" />
                <flux:error name="email" />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full">Send reset link</flux:button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" wire:navigate class="text-sm text-amber-600 hover:text-amber-500 dark:text-amber-400 font-medium transition">
                Back to sign in
            </a>
        </div>
    </flux:card>
</div>