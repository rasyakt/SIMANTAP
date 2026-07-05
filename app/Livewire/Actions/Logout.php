<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    public function __invoke(): void
    {
        $user = Auth::user();

        if ($user) {
            activity('auth')
                ->causedBy($user)
                ->event('logout')
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'email' => $user->email,
                ])
                ->log("Pengguna {$user->name} ({$user->email}) telah keluar dari sistem.");
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
