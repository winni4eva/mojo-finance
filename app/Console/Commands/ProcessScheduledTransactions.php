<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTransaction;
use App\Models\Account;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class ProcessScheduledTransactions extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process {user} {debit} {credit} {amount} {scheduleId}';

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
        [ 
            'command' => $command, 
            'user' => $user, 
            'debit' => $debit, 
            'credit' => $credit, 
            'amount' => $amount, 
            'scheduleId' => $scheduleId
        ] = $this->arguments();

        $debitAccount = Account::find($debit);
        $creditAccount = Account::find($credit);
        $userModel = User::find($user);

        ProcessTransaction::dispatch($debitAccount, $creditAccount, $userModel, $amount, $scheduleId);


        return Command::SUCCESS;
    }
}
