<?php

declare(strict_types=1);

namespace App\Data\Users;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class UserUpdateData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public string $name,
        #[Required, Email, Max(255)]
        public string $email,
        #[Required, StringType]
        public string $role,
        #[BooleanType]
        public bool $is_active = true,
    ) {}

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function rules(array $payload): array
    {
        $userId = request()->route('user');
        if (is_object($userId)) {
            $userId = $userId->getKey();
        }

        return [
            'email' => [Rule::unique('users', 'email')->ignore($userId)],
            'role' => [Rule::exists('roles', 'name')],
        ];
    }
}
