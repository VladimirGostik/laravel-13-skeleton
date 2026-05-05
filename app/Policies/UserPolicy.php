<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view users');
    }

    public function view(User $user, User $target): bool
    {
        return $user->can('view users');
    }

    public function create(User $user): bool
    {
        return $user->can('create users');
    }

    public function update(User $user, User $target): bool
    {
        return $user->can('edit users');
    }

    public function delete(User $user, User $target): bool
    {
        if ($user->getKey() === $target->getKey()) {
            return false;
        }

        return $user->can('delete users');
    }
}
