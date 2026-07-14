<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <flux:card class="!p-8">
        <div class="text-center mb-8">
            <flux:icon name="magnifying-glass" class="size-12 text-amber-500 mx-auto mb-4" />
            <flux:heading size="lg" class="mb-1">Reset password</flux:heading>
            <flux:subheading>Choose a new password for your account.</flux:subheading>
        </div>

        <form wire:submit="resetPassword" class="space-y-5">
            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" placeholder="you@example.com" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>New Password</flux:label>
                <flux:input wire:model="password" type="password" placeholder="••••••••" />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Confirm Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="••••••••" />
                <flux:error name="password_confirmation" />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full">Reset password</flux:button>
        </form>
    </flux:card>
</div>