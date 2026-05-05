<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SupportedLanguage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Spatie\Activitylog\Models\Activity;

final class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'translations' => fn () => Arr::dot((array) trans('app')),
            'canResetPassword' => fn () => Route::has('password.request'),
            'flash' => fn () => [
                'success' => $request->session()->get('flash.success'),
                'error' => $request->session()->get('flash.error'),
                'info' => $request->session()->get('flash.info'),
                'status' => $request->session()->get('status'),
            ],
            'auth' => fn () => [
                'user' => $request->user() ? [
                    'id' => $request->user()->getKey(),
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],
            'can' => function () use ($request) {
                $user = $request->user();
                if (! $user instanceof User) {
                    return [];
                }

                return [
                    'viewUsers' => $user->can('viewAny', User::class),
                    'viewRoles' => $user->can('viewAny', Role::class),
                    'viewAuditLogs' => $user->can('viewAny', Activity::class),
                    'editGlobalSettings' => $user->can('edit global settings'),
                ];
            },
            'locale' => fn () => app()->getLocale(),
            'languages' => fn () => SupportedLanguage::getForLanguageSwitcher(),
        ]);
    }
}
