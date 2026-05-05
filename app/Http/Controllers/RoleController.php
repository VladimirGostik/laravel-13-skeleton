<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\Roles\RoleDetailData;
use App\Data\Roles\RoleListItemData;
use App\Data\Roles\RoleStoreData;
use App\Data\Roles\RoleUpdateData;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use InvalidArgumentException;
use Spatie\Permission\Models\Permission;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class RoleController extends Controller implements HasMiddleware
{
    public function __construct(private readonly RoleService $service) {}

    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    public function index(): Response
    {
        $this->authorize('viewAny', Role::class);

        $roles = QueryBuilder::for(Role::query()->withCount(['users', 'permissions']))
            ->allowedFilters([AllowedFilter::scope('search')])
            ->allowedSorts(['name', 'created_at'])
            ->defaultSort('name')
            ->paginate(25)
            ->withQueryString()
            ->through(fn (Role $r) => RoleListItemData::fromModel($r));

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Role::class);

        return Inertia::render('Roles/Create', [
            'permissions' => Permission::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function store(RoleStoreData $data): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $this->service->create($data);

        return redirect()->route('roles.index')->with('flash.success', __('app.role_created'));
    }

    public function edit(Role $role): Response
    {
        $this->authorize('update', $role);

        return Inertia::render('Roles/Edit', [
            'role' => RoleDetailData::fromModel($role),
            'permissions' => Permission::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function update(RoleUpdateData $data, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        try {
            $this->service->update($role, $data);
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['name' => $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('flash.success', __('app.role_updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        try {
            $this->service->delete($role);
        } catch (InvalidArgumentException $e) {
            return back()->with('flash.error', $e->getMessage());
        }

        return redirect()->route('roles.index')->with('flash.success', __('app.role_deleted'));
    }

    private function authorize(string $ability, mixed $arguments = []): void
    {
        $user = auth()->user();
        if ($user === null || ! $user->can($ability, $arguments)) {
            throw new AuthorizationException;
        }
    }
}
