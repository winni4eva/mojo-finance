<?php

namespace App\Service;

use App\Events\TransactionFailed;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(Account $account, Account $creditAccount, int $userId, int $amount)
    {
        try {
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
                'user_id' => $userId,
            ]);

            if (! $transaction) {
                DB::rollBack();
                $this->dispatchFailedEvent($account, $creditAccount, $userId, $amount);
                return false;
            }

            DB::commit();

            return $transaction;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchFailedEvent($account, $creditAccount, $userId, $amount);
            throw $th;
        }
    }

    private function dispatchFailedEvent(Account $account, Account $creditAccount, int $userId, int $amount)
    {
        TransactionFailed::dispatch($account, $creditAccount, $userId, $amount);
    }
}
