<?php

declare(strict_types=1);

namespace App\Data\Profile;

use App\Data\Support\AuthenticatedUserReference;
use App\Enums\SupportedLanguage;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class ProfileUpdateData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public string $name,
        #[Required, Email, Max(255)]
        public string $email,
        #[Required, StringType]
        public string $locale,
        #[WithoutValidation]
        public ?AuthenticatedUserReference $userRef = null,
    ) {}

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function rules(): array
    {
        $userId = auth()->id();

        return [
            'email' => [Rule::unique('users', 'email')->ignore($userId)],
            'locale' => [Rule::in(SupportedLanguage::getCodes())],
        ];
    }
}
