<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'locale' => 'sk',
                'is_active' => true,
            ],
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        User::factory()
            ->count(5)
            ->create()
            ->each(fn (User $user) => $user->assignRole('user'));
    }
}
