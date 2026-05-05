<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

final class AppDemoCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'app:demo {--fresh : Drop all tables before migrating} {--force : Allow running in production}';

    protected $description = 'Reset the database and seed a complete demo environment.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            $this->info('Refreshing database...');
            Artisan::call('migrate:fresh', ['--force' => true], $this->output);
        } else {
            Artisan::call('migrate', ['--force' => true], $this->output);
        }

        $this->info('Seeding database...');
        Artisan::call('db:seed', ['--force' => true], $this->output);

        $this->info('Clearing permission cache...');
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Linking storage...');
        Artisan::call('storage:link', [], $this->output);

        foreach (['cache:clear', 'config:clear', 'route:clear', 'view:clear'] as $cmd) {
            Artisan::call($cmd, [], $this->output);
        }

        $this->info('Generating TypeScript types...');
        Artisan::call('typescript:transform', [], $this->output);

        $url = (string) config('app.url');
        $this->newLine();
        $this->line('┌──────────────────────────────────────────────┐');
        $this->line('│  Demo application is ready                   │');
        $this->line('│  Login:    admin@example.com                 │');
        $this->line('│  Password: password                          │');
        $this->line(sprintf('│  URL:      %-34s│', $url));
        $this->line('└──────────────────────────────────────────────┘');

        return self::SUCCESS;
    }
}
