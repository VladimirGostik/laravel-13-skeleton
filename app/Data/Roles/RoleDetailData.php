<?php

declare(strict_types=1);

namespace App\Data\Roles;

use App\Models\Role;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class RoleDetailData extends Data
{
    /**
     * @param  array<int, string>  $permissions
     */
    public function __construct(
        public int $id,
        public string $name,
        public bool $is_system,
        public array $permissions,
    ) {}

    public static function fromModel(Role $role): self
    {
        return new self(
            id: (int) $role->getKey(),
            name: $role->name,
            is_system: in_array($role->name, ['admin'], true),
            permissions: $role->permissions()->pluck('name')->all(),
        );
    }
}
