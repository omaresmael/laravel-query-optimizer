<?php

namespace Omaresmaeel\LaravelQueryOptimizer;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Omaresmaeel\LaravelQueryOptimizer\Http\Client;


class Optimizer
{
    private string $optimizedQuery = '';
    private string $reasoning = '';
    private string $suggestions = '';
    private EloquentBuilder | QueryBuilder $builder;

    public function optimize(EloquentBuilder | QueryBuilder $builder): self
    {
        $this->builder = $builder;
        $queryPrompt = $this->getPrompt();
        $this->getGptResponse($queryPrompt);
        return $this;
    }

    public function run(): array
    {
        return DB::select(DB::raw($this->optimizedQuery), $this->builder->getBindings());
    }

    public function toSql(): string
    {
        return $this->optimizedQuery;
    }
    public function explain(): array
    {
        return [
            'optimizedQuery' => $this->optimizedQuery,
            'reasoning' => $this->reasoning,
            'suggestions' => $this->suggestions,
        ];
    }
    private function getPrompt() : string
    {
        return (string) view('prompt', [
            'query' => $this->builder->toSql(),
        ]);
    }

    private function getGptResponse(string $prompt): void
    {
        $this->buildResult(Client::optimizeRequest($prompt));
    }

    private function buildResult(string $result): void
    {
        $array = [];
        foreach (explode(PHP_EOL, $result) as $element) {
            $parts = explode(": ", $element, 2);
            $key = trim(Arr::get($parts,0));
            $value = trim(Arr::get($parts,1),'\n"') ?: null;
            $array[$key] = $value;
        }

        $this->optimizedQuery = $array['optimizedQuery'];
        $this->reasoning = $array['reasoning'];
        $this->suggestions = $array['suggestions'];
    }


}