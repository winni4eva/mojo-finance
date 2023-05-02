<?php

namespace App\Models\Events;

use App\Models\Account;
use App\Models\Pipelines\AbstractUuid;

class SetAccountNumberUuid extends AbstractUuid
{
    public function mutate(Account $builder)
    {
        $builder->account_number = mt_rand();
    }
}
