<?php

declare(strict_types=1);

namespace App\Data\Roles;

use App\Models\Role;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class RoleListItemData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $users_count,
        public int $permissions_count,
        public bool $is_system,
        #[LiteralTypeScriptType('{ view: boolean, edit: boolean, delete: boolean }')]
        /** @var array<string, bool> */
        public array $can,
    ) {}

    public static function fromModel(Role $role): self
    {
        $actor = auth()->user();
        $isSystem = in_array($role->name, ['admin'], true);

        return new self(
            id: (int) $role->getKey(),
            name: $role->name,
            users_count: (int) ($role->users_count ?? $role->users()->count()),
            permissions_count: (int) ($role->permissions_count ?? $role->permissions()->count()),
            is_system: $isSystem,
            can: [
                'view' => $actor?->can('view', $role) ?? false,
                'edit' => ! $isSystem && ($actor?->can('update', $role) ?? false),
                'delete' => ! $isSystem && ($actor?->can('delete', $role) ?? false),
            ],
        );
    }
}
