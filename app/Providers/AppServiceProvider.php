<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(function (Login $event) {
            $user = $event->user;
            activity('auth')
                ->causedBy($user)
                ->event('login')
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'email' => $user->email,
                ])
                ->log("Pengguna {$user->name} ({$user->email}) telah masuk ke sistem.");
        });
    }
}
