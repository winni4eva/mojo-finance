<?php

namespace App\Filters;

use App\Filters\FilterFields\CreatedDateFilter;

class AccountFilters extends AbstractFilter
{
    protected $filters = [
        'date' => CreatedDateFilter::class,
    ];
}
