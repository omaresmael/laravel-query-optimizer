<?php

namespace Omaresmaeel\LaravelQueryOptimizer\Tests;

use Omaresmaeel\LaravelQueryOptimizer\LaravelQueryOptimizerServiceProvider as ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'openai.api_key' => 'test',
        ]);
    }

    public function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
