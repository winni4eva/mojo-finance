<?php

namespace App\Filters;

use App\Filters\FilterFields\CreatedDateFilter;
use App\Filters\FilterFields\PriceFilter;
use App\Filters\TransactionFilterFields\CreditAccountFilter;
use App\Filters\TransactionFilterFields\DebitAccountFilter;

class TransactionFilters extends AbstractFilter
{
    protected $filters = [
        'credit_account' => CreditAccountFilter::class,
        'debit_account' => DebitAccountFilter::class,
        'date' => CreatedDateFilter::class,
        'amount' => PriceFilter::class,
    ];
}
