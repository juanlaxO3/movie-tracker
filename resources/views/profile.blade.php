<x-app-layout>
    <x-slot name="header">Profile</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <flux:card class="!p-6">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </flux:card>

        <flux:card class="!p-6">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </flux:card>

        <flux:card class="!p-6">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </flux:card>
    </div>
</x-app-layout>
