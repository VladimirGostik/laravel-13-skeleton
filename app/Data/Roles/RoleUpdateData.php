<?php

declare(strict_types=1);

namespace App\Data\Roles;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class RoleUpdateData extends Data
{
    /**
     * @param  array<int, string>  $permissions
     */
    public function __construct(
        #[Required, StringType, Max(125)]
        public string $name,
        public array $permissions = [],
    ) {}

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function rules(): array
    {
        $roleId = request()->route('role');
        if (is_object($roleId)) {
            $roleId = $roleId->getKey();
        }

        return [
            'name' => [Rule::unique('roles', 'name')->ignore($roleId)],
        ];
    }
}
