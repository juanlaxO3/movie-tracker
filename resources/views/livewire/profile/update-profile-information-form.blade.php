<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public ?string $bio = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->bio = $user->bio;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <flux:heading size="sm" class="mb-1">Profile Information</flux:heading>
    <flux:subheading class="mb-6">Update your account's profile information and email address.</flux:subheading>

    <form wire:submit="updateProfileInformation" class="space-y-6">
        <flux:field>
            <flux:label>Name</flux:label>
            <flux:input wire:model="name" required autofocus autocomplete="name" />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>Email</flux:label>
            <flux:input wire:model="email" type="email" required autocomplete="username" />
            <flux:error name="email" />
        </flux:field>

        <flux:field>
            <flux:label>Bio</flux:label>
            <flux:textarea wire:model="bio" placeholder="Tell others about yourself..." rows="3" />
            <flux:error name="bio" />
        </flux:field>

        <flux:field>
            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-zinc-600 dark:text-zinc-400">
                        {{ __('Your email address is unverified.') }}
                        <button wire:click.prevent="sendVerification" class="underline hover:no-underline">{{ __('Click here to re-send the verification email.') }}</button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </flux:field>

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Save</flux:button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
