<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(Account $account, Account $creditAccount, int $amount)
    {
        DB::beginTransaction();

        $account->update([
            'amount' => $account->amount - $amount,
        ]);

        $creditAccount->update([
            'amount' => $creditAccount->amount + $amount,
        ]);

        $transaction = Transaction::create([
            'credit_account_id' => $creditAccount->id,
            'debit_account_id' => $account->id,
            'amount' => $amount,
            'user_id' => auth()->user()->id,
        ]);

        if (! $transaction) {
            DB::rollBack();

            return false;
        }

        DB::commit();

        return $transaction;
    }
}
