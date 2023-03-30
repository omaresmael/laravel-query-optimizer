<?php

namespace Omaresmaeel\LaravelQueryOptimizer\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Omaresmaeel\LaravelQueryOptimizer\LaravelQueryOptimizerServiceProvider as ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use function Orchestra\Testbench\artisan;


class TestCase extends Orchestra
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'openai.api_key' => 'test',
        ]);

    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations/');
    }
    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
