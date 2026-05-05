<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Profile\ChangePasswordData;
use App\Data\Profile\ProfileUpdateData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class ProfileService
{
    public function updateProfile(User $user, ProfileUpdateData $data): User
    {
        $user->fill([
            'name' => $data->name,
            'email' => $data->email,
            'locale' => $data->locale,
        ])->save();

        session(['locale' => $data->locale]);
        app()->setLocale($data->locale);

        return $user->fresh();
    }

    public function changePassword(User $user, ChangePasswordData $data): void
    {
        $user->password = Hash::make($data->password);
        $user->save();
    }
}
