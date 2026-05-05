<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class ExampleTest extends TestCase
{
    public function test_the_application_health_endpoint_responds(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_root_redirects_to_dashboard_for_guests(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
    }
}
