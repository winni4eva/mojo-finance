<?php
namespace App\Models\Pipelines;

use App\Models\Account;
use App\Models\Events\SetAccountNumberUuid;
use Illuminate\Support\Facades\Pipeline;

class AccountCreatingPipeline
{
    public function __construct(Account $model)
    {
        app(Pipeline::class)
            ->send($model)
            ->through([
                SetAccountNumberUuid::class,
            ]);
    }
}