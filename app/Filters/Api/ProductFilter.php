<?php

namespace App\Filters\Api;

use App\Filters\ApiFilter;

class ProductFilter extends ApiFilter
{
    protected $allowedParams = [
        'name' => ['eq', 'like'],
        'cost' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte'],
        'price' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte']
    ];

    protected $orderByFields = [
        'id',
        'cost',
        'price'
    ];
}
