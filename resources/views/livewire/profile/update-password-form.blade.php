<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <flux:heading size="sm" class="mb-1">Update Password</flux:heading>
    <flux:subheading class="mb-6">Ensure your account is using a long, random password to stay secure.</flux:subheading>

    <form wire:submit="updatePassword" class="space-y-6">
        <flux:field>
            <flux:label>Current Password</flux:label>
            <flux:input wire:model="current_password" type="password" autocomplete="current-password" />
            <flux:error name="current_password" />
        </flux:field>

        <flux:field>
            <flux:label>New Password</flux:label>
            <flux:input wire:model="password" type="password" autocomplete="new-password" />
            <flux:error name="password" />
        </flux:field>

        <flux:field>
            <flux:label>Confirm Password</flux:label>
            <flux:input wire:model="password_confirmation" type="password" autocomplete="new-password" />
            <flux:error name="password_confirmation" />
        </flux:field>

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Save</flux:button>

            <x-action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
