<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Users\UserStoreData;
use App\Data\Users\UserUpdateData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class UserService
{
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
