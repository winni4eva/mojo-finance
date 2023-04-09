<?php

namespace App\Filters;

use App\Filters\SharedFilterFields\CreatedDateFilter;
use App\Filters\SharedFilterFields\PriceFilter;

class AccountFilters extends AbstractFilter implements FilterInterface
{
    protected $filters = [
        'date' => CreatedDateFilter::class,
        'price' => PriceFilter::class,
    ];
}
