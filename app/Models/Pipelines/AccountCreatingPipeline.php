<?php
namespace App\Models\Pipelines;

use App\Models\Account;
use App\Models\Events\SetAccountNumberUuid;
use Illuminate\Pipeline\Pipeline;

class AccountCreatingPipeline
{
    public function __construct(Account $model)
    {
        logger('Setting Pipeline -> ');
        logger($model);
        app(Pipeline::class)
            ->send($model)
            ->through([
                SetAccountNumberUuid::class,
            ])
            ->thenReturn();
    }
}