<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\LogAuthenticationActivity;
use App\Models\Role;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Services\ProfileService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserService::class);
        $this->app->singleton(RoleService::class);
        $this->app->singleton(ProfileService::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);

        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        Event::listen(Login::class, [LogAuthenticationActivity::class, 'handleLogin']);
        Event::listen(Logout::class, [LogAuthenticationActivity::class, 'handleLogout']);
        Event::listen(Failed::class, [LogAuthenticationActivity::class, 'handleFailed']);
    }
}
