<?php

declare(strict_types=1);

namespace App\Data\Users;

use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class UserListItemData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public ?string $role,
        public bool $is_active,
        public ?string $created_at,
        #[LiteralTypeScriptType('{ view: boolean, edit: boolean, delete: boolean }')]
        /** @var array<string, bool> */
        public array $can,
    ) {}

    public static function fromModel(User $user): self
    {
        $actor = auth()->user();

        return new self(
            id: (string) $user->getKey(),
            name: $user->name,
            email: $user->email,
            role: $user->getRoleNames()->first(),
            is_active: (bool) $user->is_active,
            created_at: $user->created_at instanceof Carbon ? $user->created_at->toIso8601String() : null,
            can: [
                'view' => $actor?->can('view', $user) ?? false,
                'edit' => $actor?->can('update', $user) ?? false,
                'delete' => $actor?->can('delete', $user) ?? false,
            ],
        );
    }
}
