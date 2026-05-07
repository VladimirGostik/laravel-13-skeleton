<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\Users\UserDetailData;
use App\Data\Users\UserIndexFilterData;
use App\Data\Users\UserStoreData;
use App\Data\Users\UserUpdateData;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

final class UserController extends Controller implements HasMiddleware
{
    public function __construct(private readonly UserService $service) {}

    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    public function index(UserIndexFilterData $filters): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Users/Index', [
            'users' => $this->service->list($filters),
            'filters' => $filters,
            'roles' => Role::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Users/Create', [
            'roles' => Role::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function store(UserStoreData $data): RedirectResponse
    {
        $this->authorize('create', User::class);

        $this->service->create($data);

        return redirect()->route('users.index')->with('flash.success', __('app.user_created'));
    }

    public function show(User $user): Response
    {
        $this->authorize('view', $user);

        return Inertia::render('Users/Show', [
            'user' => UserDetailData::fromModel($user->load('roles')),
        ]);
    }

    public function edit(User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('Users/Edit', [
            'user' => UserDetailData::fromModel($user->load('roles')),
            'roles' => Role::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function update(UserUpdateData $data, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $this->service->update($user, $data);

        return redirect()->route('users.index')->with('flash.success', __('app.user_updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $this->service->delete($user);

        return redirect()->route('users.index')->with('flash.success', __('app.user_deleted'));
    }
}
