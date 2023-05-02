<?php

namespace App\Models\Events;

use App\Models\Account;

class SetAccountNumberUuid extends AbstractUuid
{
    public function mutate(Account $builder)
    {
        $builder->account_number = mt_rand();
    }
}
