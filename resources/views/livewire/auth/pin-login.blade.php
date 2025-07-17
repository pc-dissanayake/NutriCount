<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|digits:6')]
    public string $pin = '';

    /**
     * Handle an incoming PIN authentication request.
     */
    public function loginWithPin(): void
    {
        $this->validate();

        // Get allowed PINs from env and check if entered PIN is valid
        $allowedPins = explode(',', env('PIN_LOGINS', ''));
        if (!in_array($this->pin, $allowedPins)) {
            throw ValidationException::withMessages([
                'pin' => __('Invalid PIN.'),
            ]);
        }

        // Always login as a guest user (create if not exists)
        $guest = \App\Models\User::firstOrCreate(
            ['email' => 'guest@nutricount.local'],
            [
                'name' => 'Guest User',
                'password' => bcrypt(uniqid()), // random password
            ]
        );
        Auth::login($guest);
        Session::put('pin_guest', $this->pin);
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Enter PIN')" :description="__('Enter your 6-digit PIN to log in')" />

    <form wire:submit="loginWithPin" class="flex flex-col gap-6">
        <flux:input
            wire:model="pin"
            :label="__('PIN')"
            type="password"
            required
            maxlength="6"
            minlength="6"
            pattern="[0-9]{6}"
            autocomplete="off"
            placeholder="000000"
        />
        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
        </div>
    </form>
</div>
