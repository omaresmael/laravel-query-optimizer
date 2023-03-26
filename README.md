# laravel-query-optimizer
Optimize laravel DB or eloquent queries using openAI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/omaresmaeel/laravel-query-optimizer.svg?style=flat-square)](https://packagist.org/packages/omaresmaeel/laravel-query-optimizer)
[![Total Downloads](https://img.shields.io/packagist/dt/omaresmaeel/laravel-query-optimizer.svg?style=flat-square)](https://packagist.org/packages/omaresmaeel/laravel-query-optimizer)
# Installation

First, install the package using composer
```bash
composer require omaresmael/laravel-query-optimizer
```

Then, add the openAPI key to your `.env` file
```bash
OPENAI_API_KEY=sk-...
```
and that's it, you are ready to go

# Usage
this package can help you optimize your eloquent or DB queries using openAI 
```php
User::query() //can be applied to `DB facade` as well
    ->select('id', 'name')
    ->whereHas('roles', function ($query) {
        $query->where('name', 'author');
    })
    ->whereHas('posts', function ($query) {
        $query->where('title', 'Awesome post');
    })->optimize()->get()
```

**the package has the following methods**

### `optimize()`
This method is responsible for optimizing the query and return an instance of the `Optimizer` class.
```php
User::query() 
   ->select('id', 'name')
    ->whereHas('roles', function ($query) {
        $query->where('name', 'author');
    })
    ->whereHas('posts', function ($query) {
        $query->where('title', 'Awesome post');
    })->optimize()

//old query => select `id`, `name` from `users` where exists (select * from `roles` inner join `role_user` on `roles`.`id` = `role_user`.`role_id` where `users`.`id` = `role_user`.`user_id` and `type` = ?) and exists (select * from `posts` where `users`.`id` = `posts`.`user_id` and `title` = ?)

//optimized query => SELECT `id`, `name` FROM `users` INNER JOIN `role_user` ON `users`.`id` = `role_user`.`user_id` INNER JOIN `roles` ON `roles`.`id` = `role_user`.`role_id` INNER JOIN `posts` ON `users`.`id` = `posts`.`user_id` WHERE `type` = ? AND `title` = ?
```

### `toSql()`
this method will return the optimized query as a string
```php
User::query()
    ->select('id', 'name')
    ->whereHas('roles', function ($query) {
        $query->where('type', 'author');
    })
    ->whereHas('posts', function ($query) {
        $query->where('title', 'Awesome post');
    })->optimize()->toSql()
    
//output => SELECT `id`, `name` FROM `users` INNER JOIN `role_user` ON `users`.`id` = `role_user`.`user_id` INNER JOIN `roles` ON `roles`.`id` = `role_user`.`role_id` INNER JOIN `posts` ON `users`.`id` = `posts`.`user_id` WHERE `type` = ? AND `title` = ?

```

### `get()`
this method will run the optimized query that is generated by `optimize()` method and return the result <br> <br>
⚠️ make sure to know the query you are running before using this method, as it will run the optimized query and not the original query
```php
    User::query()
    ->select('id', 'name')
    ->whereHas('roles', function ($query) {
        $query->where('type', 'author');
    })
    ->whereHas('posts', function ($query) {
        $query->where('title', 'Awesome post');
    })->optimize()->get()
```

### `explain()`
this method will return a key-value array that contains the optimized query, the reasoning behind performing such optimization, and suggestions to manually optimize the query even further
```php
User::query()
    ->select('id', 'name')
    ->whereHas('roles', function ($query) {
        $query->where('type', 'author');
    })
    ->whereHas('posts', function ($query) {
        $query->where('title', 'Awesome post');
    })->optimize()->explain()
```
the array format will be
```php
[
'optimizedQuery' => 'SELECT `id`, `name` FROM `users` INNER JOIN `role_user` ON `users`.`id` = `role_user`.`user_id` INNER JOIN `roles` ON `roles`.`id` = `role_user`.`role_id` INNER JOIN `posts` ON `users`.`id` = `posts`.`user_id` WHERE `type` = ? AND `title` = ?' // the optimized query
'reasoning'      => 'This query optimizes the original query by using JOINs to reduce the number of subqueries and improve the performance of the query. By using JOINs, the query can access the data from multiple tables in a single query, instead of having to make multiple subqueries.' //the reasoning behind performing such optimization,
'suggestions'    => 'It may be beneficial to add an index on the `type` and `title` columns to further improve the performance of the query.' //suggestions to manually optimize the query even further
]
```

## Credits

- [Omar Esmaeel](https://github.com/omaresmael)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Inspiration
This package is inspired by [laravel-ask-database](https://github.com/beyondcode/laravel-ask-database) package <br>
Thanks for the great work [Marcel Pociot](https://github.com/mpociot)



