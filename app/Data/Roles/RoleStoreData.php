<?php

declare(strict_types=1);

namespace App\Data\Roles;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class RoleStoreData extends Data
{
    /**
     * @param  array<int, string>  $permissions
     */
    public function __construct(
        #[Required, StringType, Max(125), Unique('roles', 'name')]
        public string $name,
        public array $permissions = [],
    ) {}
}
