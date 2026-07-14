<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <flux:heading size="sm" class="mb-1">Delete Account</flux:heading>
    <flux:subheading class="mb-6">Once your account is deleted, all of its resources and data will be permanently deleted.</flux:subheading>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger">Delete Account</flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" class="w-full sm:max-w-md">
        <form wire:submit="deleteUser" class="p-6 space-y-6">
            <flux:heading size="sm">Are you sure you want to delete your account?</flux:heading>
            <flux:subheading>Enter your password to confirm you would like to permanently delete your account.</flux:subheading>

            <flux:field>
                <flux:input wire:model="password" type="password" placeholder="Password" />
                <flux:error name="password" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Delete Account</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
