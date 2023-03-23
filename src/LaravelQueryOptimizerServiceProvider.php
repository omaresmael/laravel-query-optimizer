<?php

namespace src;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\ServiceProvider;


class LaravelQueryOptimizerServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        EloquentBuilder::macro('optimize', function () {
            return app(Optimizer::class)->optimize($this);
        });

        QueryBuilder::macro('optimize', function () {
            return app(Optimizer::class)->optimize($this);
        });
    }

}
{

}