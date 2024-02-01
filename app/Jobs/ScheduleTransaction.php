<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\User;
use App\Service\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScheduleTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Account $account,
        protected Account $creditAccount,
        protected User $user,
        protected int $amount,
        protected string $period
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TransactionService $transactionService)
    {
        $transactionService->createScheduledTransaction(
            $this->account,
            $this->creditAccount,
            $this->amount,
            $this->user->id,
            $this->period
        );
    }
}
