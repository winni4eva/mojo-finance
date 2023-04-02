<?php

namespace App\Filters\TransactionFilterFields;

class CreditAccountFilter
{
    public function __invoke($query, $creditAccount)
    {
        return $query->whereHas('creditAccount', fn ($query) => $query->where('credit_account_id', $creditAccount));
    }
}
