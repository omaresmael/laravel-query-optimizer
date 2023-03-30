<?php

use Illuminate\Support\Facades\DB;
use Omaresmaeel\LaravelQueryOptimizer\Optimizer;


beforeEach(function () {
   $this->builder = DB::query()
        ->select('users.id', 'users.name')
        ->from('users')
        ->whereExists(function ($query) {
            $query->select('*')
                ->from('posts')
                ->whereColumn('posts.user_id', 'users.id')
                ->where('title', 'Awesome post');
        });

    $this->arrayResponse = [
        'optimizedQuery' => 'SELECT `users`.`id`, `users`.`name` FROM `users` INNER JOIN `posts` ON `posts`.`user_id` = `users`.`id` WHERE `title` = ?',
        'reasoning' => 'By using an INNER JOIN, we can reduce the number of queries needed to get the desired result, as the JOIN will only return the rows that match the condition in the WHERE clause.',
        'suggestions' => 'If the query is being used frequently, it may be beneficial to add an index on the `title` column of the `posts` table to further optimize the query.',
    ];
});

it('returns the optimized sql query as a string', function () {
    $optimizer = new Optimizer(mockClient($this->arrayResponse));
    $this->expect($optimizer->optimize($this->builder)->toSql())->toBe($this->arrayResponse['optimizedQuery']);
});

it('returns the optimized query, reasoning, and suggestions in  key-value array', function () {
    $explainArray = [
        'optimizedQuery' => $this->arrayResponse['optimizedQuery'],
        'reasoning' => $this->arrayResponse['reasoning'],
        'suggestions' => $this->arrayResponse['suggestions'],
    ];
    $optimizer = new Optimizer(mockClient($this->arrayResponse));

    $this->expect($optimizer->optimize($this->builder)->explain())->toBe($explainArray);
});

it('throws exception if the response is not correctly formatted', function () {
    $this->expectException(RuntimeException::class);

    $optimizer = new Optimizer(mockClient($this->arrayResponse, false));
    $optimizer->optimize($this->builder)->explain();
});
it('gets the same result as the original query', function () {
    $optimizer = new Optimizer(mockClient($this->arrayResponse));
    $optimizedQuery = $optimizer->optimize($this->builder)->get();
    $originalQuery = $this->builder->get();

    $this->expect($optimizedQuery)-> toEqual($originalQuery);

});