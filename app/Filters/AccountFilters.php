<?php

namespace App\Filters;

use App\Filters\FilterFields\CreatedDateFilter;
use App\Filters\FilterFields\PriceFilter;

class AccountFilters extends AbstractFilter
{
    protected $filters = [
        'date' => CreatedDateFilter::class,
        'price' => PriceFilter::class,
    ];
}
