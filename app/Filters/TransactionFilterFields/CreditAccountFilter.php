<?php

namespace App\Filters\TransactionFilterFields;

class CreditAccountFilter
{
    public function __invoke($query, $creditAccount)
    {
        return $query->whereHas('creditAccount', function ($query) use ($creditAccount) {
            $query->where('credit_account_id', $creditAccount);
        });
    }
}
