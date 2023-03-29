<?php

use Omaresmaeel\LaravelQueryOptimizer\Http\Client;
use Omaresmaeel\LaravelQueryOptimizer\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
function mockClient(array $arrayResponse, bool $correctFormat = true): Client
{
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('optimizeRequest')
        ->once()
        ->andReturn(result($arrayResponse, $correctFormat));

    return $client;
}

function result(array $arrayResponse, bool $correctFormat): string
{
    if (! $correctFormat) {
        return 'bad response due to api request issue';
    }

    return
        "
        optimizedQuery: $arrayResponse[optimizedQuery]
        reasoning: $arrayResponse[reasoning]
        suggestions: $arrayResponse[suggestions]
        ";
}
