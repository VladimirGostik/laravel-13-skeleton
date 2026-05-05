<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

final class LogAuthenticationActivity
{
    public function handleLogin(Login $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
            ->event('login')
            ->log('User logged in');
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user === null) {
            return;
        }

        activity('auth')
            ->causedBy($event->user)
            ->withProperties(['ip' => request()->ip()])
            ->event('logout')
            ->log('User logged out');
    }

    public function handleFailed(Failed $event): void
    {
        activity('auth')
            ->withProperties([
                'email' => $event->credentials['email'] ?? null,
                'ip' => request()->ip(),
            ])
            ->event('failed')
            ->log('Authentication failed');
    }

    /**
     * @return array<class-string, string>
     */
    public function subscribe(): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
        ];
    }
}
