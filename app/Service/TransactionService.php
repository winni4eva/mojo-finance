<?php

namespace App\Service;

use App\Events\TransactionFailed;
use App\Models\Account;
use App\Models\ScheduledTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(Account $account, Account $creditAccount, User $user, int $amount)
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
                'user_id' => $user->id,
            ]);

            if (! $transaction) {
                DB::rollBack();
                $this->dispatchFailedEvent($account, $creditAccount, $user, $amount);

                return false;
            }

            DB::commit();

            return $transaction;
        } catch (\Throwable $th) {
            // Add audit logs
            DB::rollBack();
            $this->dispatchFailedEvent($account, $creditAccount, $user, $amount);
            throw $th;
        }
    }

    public function createScheduledTransaction(Account $account)
    {
        $scheduledTransaction = ScheduledTransaction::create([
            'account_id' => (int) request('credit_account'),
            'debit_account_id' => $account->id,
            'amount' => request('amount'),
            'user_id' => auth()->user()->id,
            'period' => request('period'),
            'time' => request('time'),
        ]);

        if (! $scheduledTransaction) {
            /** Add Scheduled Transaction Failed notification */
            // $this->dispatchFailedEvent($account, $creditAccount, $userId, $amount);
            return false;
        }

        return $scheduledTransaction;
    }

    private function dispatchFailedEvent(Account $account, Account $creditAccount, User $user, int $amount)
    {
        TransactionFailed::dispatch($account, $creditAccount, $user, $amount);
    }
}
