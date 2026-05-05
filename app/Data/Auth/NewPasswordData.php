<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class NewPasswordData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $token,
        #[Required, Email, Max(255)]
        public string $email,
        #[Required, StringType, Min(8), Confirmed]
        public string $password,
        #[Required, StringType]
        public string $password_confirmation,
    ) {}
}
