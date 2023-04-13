<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class ProcessScheduledTransactions extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process {debitAccount} {creditAccount} {userId} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send user transactions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        [
            'debitAccount' => $debitAccount,
            'creditAccount' => $creditAccount,
            'userId' => $userId,
            'amount' => $amount
        ] = $this->arguments();
        
        return Command::SUCCESS;
    }
}
