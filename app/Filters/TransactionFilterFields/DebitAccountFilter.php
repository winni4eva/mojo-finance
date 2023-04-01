<?php

namespace App\Filters\TransactionFilterFields;

class DebitAccountFilter
{
    public function __invoke($query, $debitAccount)
    {
        return $query->whereHas('debitAccount', function ($query) use ($debitAccount) {
            $query->where('debit_account_id', $debitAccount);
        });
    }
}
