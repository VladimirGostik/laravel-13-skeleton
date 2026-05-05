<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Roles\RoleStoreData;
use App\Data\Roles\RoleUpdateData;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final readonly class RoleService
{
    /**
     * @var array<int, string>
     */
    public const SYSTEM_ROLES = ['admin'];

    public function create(RoleStoreData $data): Role
    {
        return DB::transaction(function () use ($data) {
            /** @var Role $role */
            $role = Role::create(['name' => $data->name, 'guard_name' => 'web']);
            $role->syncPermissions($data->permissions);

            return $role->fresh(['permissions']);
        });
    }

    public function update(Role $role, RoleUpdateData $data): Role
    {
        if (in_array($role->name, self::SYSTEM_ROLES, true) && $role->name !== $data->name) {
            throw new InvalidArgumentException('System roles cannot be renamed.');
        }

        return DB::transaction(function () use ($role, $data) {
            $role->update(['name' => $data->name]);
            $role->syncPermissions($data->permissions);

            return $role->fresh(['permissions']);
        });
    }

    public function delete(Role $role): void
    {
        if (in_array($role->name, self::SYSTEM_ROLES, true)) {
            throw new InvalidArgumentException('System roles cannot be deleted.');
        }

        if ($role->users()->exists()) {
            throw new InvalidArgumentException('Role is still attached to users and cannot be deleted.');
        }

        $role->delete();
    }
}
