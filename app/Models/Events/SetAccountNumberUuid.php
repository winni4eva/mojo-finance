<?php

namespace App\Models\Events;

use App\Models\Account;
use App\Models\Pipelines\AbstractUuid;
use Illuminate\Support\Str;

class SetAccountNumberUuid extends AbstractUuid
{
    // public function __construct(Account $model)
    // {
    //     logger('Model Account Number');
    //     $model->account_number = Str::uuid();
    // }

    public function mutate(Account $builder)
    {
        logger('Model Account Number');
        logger($builder);
        $builder->account_number = Str::uuid();
    }
}
