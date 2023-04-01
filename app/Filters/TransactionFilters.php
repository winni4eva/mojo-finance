<?php

namespace App\Filters;

use App\Filters\TransactionFilterFields\CreditAccountFilter;
use App\Filters\TransactionFilterFields\DebitAccountFilter;

class TransactionFilters extends AbstractFilter
{
    protected $filters = [
        'credit_account' => CreditAccountFilter::class,
        'debit_account' => DebitAccountFilter::class,
    ];

    public function apply($query)
    {
        foreach ($this->receivedFilters() as $name => $value) {
            $filterInstance = new $this->filters[$name];
            $query = $filterInstance($query, $value);
        }

        return $query;
    }
}
