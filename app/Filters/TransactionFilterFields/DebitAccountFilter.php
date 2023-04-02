<?php

namespace App\Filters\TransactionFilterFields;

class DebitAccountFilter
{
    public function __invoke($query, $debitAccount)
    {
        return $query->whereHas('debitAccount', fn ($query) => $query->where('debit_account_id', $debitAccount));
    }
}
