<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum SupportedLanguage: string
{
    case SLOVAK = 'sk';
    case ENGLISH = 'en';

    public function getDisplayName(): string
    {
        return match ($this) {
            self::SLOVAK => 'Slovenčina',
            self::ENGLISH => 'English',
        };
    }

    public function getFlag(): string
    {
        return match ($this) {
            self::SLOVAK => '🇸🇰',
            self::ENGLISH => '🇬🇧',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function getCodes(): array
    {
        return array_map(fn (self $l) => $l->value, self::cases());
    }

    /**
     * @return array<int, array{code: string, name: string, flag: string}>
     */
    public static function getForLanguageSwitcher(): array
    {
        return array_map(
            fn (self $l) => [
                'code' => $l->value,
                'name' => $l->getDisplayName(),
                'flag' => $l->getFlag(),
            ],
            self::cases(),
        );
    }

    public static function isSupported(string $code): bool
    {
        return self::tryFrom($code) !== null;
    }

    public static function getDefault(): self
    {
        return self::SLOVAK;
    }
}
