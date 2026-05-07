<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Users\UserIndexFilterData;
use App\Data\Users\UserListItemData;
use App\Data\Users\UserStoreData;
use App\Data\Users\UserUpdateData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class UserService
{
    /**
     * @return LengthAwarePaginator<int, UserListItemData>
     */
    public function list(UserIndexFilterData $filters): LengthAwarePaginator
    {
        $op = config('database.default') === 'pgsql' ? 'ilike' : 'like';

        return QueryBuilder::for(User::query()->with('roles'))
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
    }

    public function create(UserStoreData $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
                'is_active' => $data->is_active,
                'email_verified_at' => now(),
                'locale' => 'sk',
            ]);

            $user->syncRoles([$data->role]);

            return $user->fresh(['roles']);
        });
    }

    public function update(User $user, UserUpdateData $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name' => $data->name,
                'email' => $data->email,
                'is_active' => $data->is_active,
            ]);

            $user->syncRoles([$data->role]);

            return $user->fresh(['roles']);
        });
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
