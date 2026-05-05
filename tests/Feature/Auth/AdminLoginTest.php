<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_admin_credentials_can_authenticate(): void
    {
        $this->seed([PermissionSeeder::class, UserSeeder::class]);

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();
        $this->assertTrue($admin->hasRole('admin'));

        $response = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_page_renders_for_guests(): void
    {
        $this->get(route('login'))->assertOk();
    }
}
