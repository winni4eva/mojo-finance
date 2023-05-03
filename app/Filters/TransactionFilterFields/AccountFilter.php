<?php

namespace App\Filters\TransactionFilterFields;

class AccountFilter
{
    public function __invoke($query, $account)
    {
        return $query->whereHas('account', fn ($query) => $query->where('account_id', $account));
    }
}
