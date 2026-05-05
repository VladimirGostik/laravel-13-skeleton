<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

final class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'users' => User::query()->count(),
                'activeUsers' => User::query()->where('is_active', true)->count(),
                'auditLogs' => Activity::query()->count(),
            ],
        ]);
    }
}
