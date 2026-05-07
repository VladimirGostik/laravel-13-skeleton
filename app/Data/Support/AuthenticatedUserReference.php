<?php

declare(strict_types=1);

namespace App\Data\Support;

use App\Models\User;
use Spatie\LaravelData\Data;

final class AuthenticatedUserReference extends Data
{
    public function __construct(public ?string $id) {}

    public static function current(): self
    {
        /** @var User|null $user */
        $user = auth()->user();

        $key = $user?->getKey();

        return new self($key !== null ? (string) $key : null);
    }
}
