<?php

declare(strict_types=1);

namespace App\Data\Support;

use App\Models\User;
use Spatie\LaravelData\Data;

final class AuthenticatedUserReference extends Data
{
    public function __construct(public ?int $id) {}

    public static function current(): self
    {
        /** @var User|null $user */
        $user = auth()->user();

        return new self($user?->getKey());
    }
}
