<?php

declare(strict_types=1);

namespace App\Data\Users;

use App\Models\User;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class UserDetailData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public ?string $role,
        public bool $is_active,
        public string $locale,
        public ?string $email_verified_at,
        public ?string $created_at,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: (string) $user->getKey(),
            name: $user->name,
            email: $user->email,
            role: $user->getRoleNames()->first(),
            is_active: (bool) $user->is_active,
            locale: (string) $user->locale,
            email_verified_at: $user->email_verified_at?->toIso8601String(),
            created_at: $user->created_at?->toIso8601String(),
        );
    }
}
