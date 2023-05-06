<?php

namespace App\Console;

use App\Models\ScheduledTransaction;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->runScheduledTransactions($schedule);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function runScheduledTransactions(Schedule $schedule)
    {
        foreach (ScheduledTransaction::lazy() as $transaction) {
            /**
             * Setup Spatie ray to inspect query performance
             */
            $taskName = 'stransact:'.$transaction->id.':user:'.$transaction->user_id;
            $command = "transactions:process {$transaction->user_id} {$transaction->debit_account_id} {$transaction->account_id} {$transaction->amount} {$transaction->id}";

            $schedule->command($command)
                ->name($taskName)
                ->emailOutputTo(config('mojo.email'))
                ->withoutOverlapping()
                ->runInBackground()
                ->when($transaction->period->isCurrentMinute());
        }
    }
}
