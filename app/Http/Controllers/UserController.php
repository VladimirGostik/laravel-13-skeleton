<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\Users\UserDetailData;
use App\Data\Users\UserIndexFilterData;
use App\Data\Users\UserListItemData;
use App\Data\Users\UserStoreData;
use App\Data\Users\UserUpdateData;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

        $op = config('database.default') === 'pgsql' ? 'ilike' : 'like';

        $users = QueryBuilder::for(User::query()->with('roles'))
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $q, $value) use ($op): void {
                    $term = '%' . $value . '%';
                    $q->where(function (Builder $sub) use ($op, $term): void {
                        $sub->where('name', $op, $term)->orWhere('email', $op, $term);
                    });
                }),
                AllowedFilter::callback('role', function (Builder $q, $value): void {
                    $q->whereHas('roles', fn (Builder $r) => $r->where('name', $value));
                }),
                AllowedFilter::exact('is_active'),
            ])
            ->allowedSorts(['name', 'email', 'created_at'])
            ->defaultSort('-created_at')
            ->paginate($filters->perPage)
            ->withQueryString()
            ->through(fn (User $u) => UserListItemData::fromModel($u));

        return Inertia::render('Users/Index', [
            'users' => $users,
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

    private function authorize(string $ability, mixed $arguments = []): void
    {
        $user = auth()->user();
        if ($user === null || ! $user->can($ability, $arguments)) {
            throw new AuthorizationException;
        }
    }
}
