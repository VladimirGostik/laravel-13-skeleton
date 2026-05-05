<?php

declare(strict_types=1);

namespace App\Data\Language;

use App\Enums\SupportedLanguage;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class LanguageSwitchData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $locale,
    ) {}

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function rules(): array
    {
        return [
            'locale' => [Rule::in(SupportedLanguage::getCodes())],
        ];
    }
}
