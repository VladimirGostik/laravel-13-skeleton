<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

final class PermissionSeeder extends Seeder
{
    /**
     * @var array<int, string>
     */
    private const PERMISSIONS = [
        'view users',
        'create users',
        'edit users',
        'delete users',
        'view roles',
        'create roles',
        'edit roles',
        'delete roles',
        'view audit logs',
        'edit global settings',
        'view api docs',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $name) {
            Permission::findOrCreate($name, 'web');
        }

        /** @var Role $admin */
        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(Permission::all());

        Role::findOrCreate('user', 'web');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
