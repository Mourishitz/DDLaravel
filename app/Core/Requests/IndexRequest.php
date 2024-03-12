<?php

namespace App\Core\Requests;

trait IndexRequest
{
    protected array $indexRules = [
        'limit' => ['nullable', 'integer'],
        'paginate' => ['nullable', 'boolean', 'max:1'],
        'page' => ['nullable', 'integer'],
    ];
}
