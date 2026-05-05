<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

final class AppDemoCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_demo_command_creates_admin_account(): void
    {
        $exit = Artisan::call('app:demo', ['--force' => true]);
        $this->assertSame(0, $exit);

        $admin = User::query()->where('email', 'admin@example.com')->first();
        $this->assertNotNull($admin);
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->is_active);
    }
}
