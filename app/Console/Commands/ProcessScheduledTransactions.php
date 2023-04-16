<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTransaction;
use App\Models\Account;
use App\Models\ScheduledTransaction;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class ProcessScheduledTransactions extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled account transactions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $totalScheduledTransactions = ScheduledTransaction::count();
 
        $bar = $this->output->createProgressBar($totalScheduledTransactions);
        
        $bar->start();
        
        foreach (ScheduledTransaction::cursor() as $transaction) {
            $debitAccount = Account::find($transaction->debit_account_id);
            $creditAccount = Account::find($transaction->account_id);

            ProcessTransaction::dispatch($debitAccount, $creditAccount, $transaction->user_id, $transaction->amount);

            $transaction->delete();

            $bar->advance();
        }
        
        $bar->finish();

        return Command::SUCCESS;
    }
}
