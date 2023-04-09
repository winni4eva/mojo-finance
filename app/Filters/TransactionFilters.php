<?php

namespace App\Filters;

use App\Filters\SharedFilterFields\CreatedDateFilter;
use App\Filters\SharedFilterFields\PriceFilter;
use App\Filters\TransactionFilterFields\CreditAccountFilter;
use App\Filters\TransactionFilterFields\DebitAccountFilter;

class TransactionFilters extends AbstractFilter implements FilterInterface
{
    protected $filters = [
        'credit_account' => CreditAccountFilter::class,
        'debit_account' => DebitAccountFilter::class,
        'date' => CreatedDateFilter::class,
        'price' => PriceFilter::class,
    ];
}
