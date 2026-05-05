<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class LoginData extends Data
{
    public function __construct(
        #[Required, Email, Max(255)]
        public string $email,
        #[Required, StringType, Max(255)]
        public string $password,
        public bool $remember = false,
    ) {}
}
