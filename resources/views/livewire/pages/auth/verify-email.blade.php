<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <flux:card class="!p-8 text-center">
        <flux:icon name="film" class="size-12 text-amber-500 mx-auto mb-4" />
        <flux:heading size="lg" class="mb-2">Verify your email</flux:heading>
        <flux:subheading class="mb-6">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</flux:subheading>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-sm text-green-700 dark:text-green-400">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <flux:button wire:click="sendVerification" variant="primary" class="w-full">Resend Verification Email</flux:button>
            <button wire:click="logout" type="submit" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 transition">
                Log Out
            </button>
        </div>
    </flux:card>
</div>