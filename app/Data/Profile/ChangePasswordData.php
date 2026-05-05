<?php

declare(strict_types=1);

namespace App\Data\Profile;

use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\CurrentPassword;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class ChangePasswordData extends Data
{
    public function __construct(
        #[Required, StringType, CurrentPassword]
        public string $current_password,
        #[Required, StringType, Min(8), Confirmed]
        public string $password,
        #[Required, StringType]
        public string $password_confirmation,
    ) {}
}
