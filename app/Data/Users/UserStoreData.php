<?php

declare(strict_types=1);

namespace App\Data\Users;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class UserStoreData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public string $name,
        #[Required, Email, Max(255), Unique('users', 'email')]
        public string $email,
        #[Required, StringType, Min(8), Confirmed]
        public string $password,
        #[Required, StringType]
        public string $password_confirmation,
        #[Required, StringType]
        public string $role,
        #[BooleanType]
        public bool $is_active = true,
    ) {}

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function rules(): array
    {
        return [
            'role' => [Rule::exists('roles', 'name')],
        ];
    }
}
