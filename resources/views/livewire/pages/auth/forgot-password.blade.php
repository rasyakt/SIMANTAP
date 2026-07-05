<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
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
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
        <p class="mt-1.5 text-sm text-gray-500">Masukkan email Anda dan kami akan mengirimkan tautan reset password</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" placeholder="nama@email.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-primary-button class="w-full">
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-500 transition-colors">&larr; Kembali ke halaman masuk</a>
        </p>
    </form>
</div>
