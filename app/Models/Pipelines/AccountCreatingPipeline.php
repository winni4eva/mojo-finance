<?php
namespace App\Models\Pipelines;

use App\Models\Account;
use App\Models\Events\SetAccountUuid;

class AccountCreatingPipeline
{
    public function __construct(Account $model)
    {
        app(Pipeline::class)
            ->send($model)
            ->through([
                SetAccountUuid::class,
            ]);
    }
}