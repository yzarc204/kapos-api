<?php

namespace App\Filters;

use Illuminate\Http\Request;


class ApiFilter
{
    // private $allowedParams = [
    //     'name' => ['eq', 'like'],
    //     'cost' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte'],
    //     'price' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte']
    // ];

    protected $allowedParams = [];

    protected $operators = [
        'eq' => '=',
        'neq' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'like' => 'LIKE'
    ];

    // protected $orderByFields = [
    //     'id',
    //     'cost',
    //     'price'
    // ];
    protected $orderByFields = [];

    protected $orderByAlgorimths = [
        'asc',
        'desc',
        'ASC',
        'DESC'
    ];

    protected $defaultOrderBy = [
        'id',
        'desc'
    ];

    public function getFilterQuery(Request $request)
    {
        $filters = [];

        foreach ($this->allowedParams as $param => $operators) {
            $query = $request->query($param);
            if (!isset($query)) {
                continue;
            }

            foreach ($query as $operator => $value) {
                if (isset($this->operators[$operator]) && in_array($operator, $this->allowedParams[$param])) {
                    $filters[] = [$param, $this->operators[$operator], $value];
                }
            }
        }

        return $filters;
    }

    public function getOrderByQuery(Request $request)
    {
        $query = $request->query('order_by');
        if (!isset($query)) {
            return $this->defaultOrderBy;
        }

        foreach ($query as $field => $algo) {
            if (!in_array($field, $this->orderByFields)) {
                return $this->defaultOrderBy;
            }

            if (!in_array($algo, $this->orderByAlgorimths)) {
                return $this->defaultOrderBy;
            }

            return [$field, $algo];
        }

        return $this->defaultOrderBy;
    }
}
