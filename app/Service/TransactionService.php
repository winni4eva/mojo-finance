<?php

namespace App\Service;

use App\Events\TransactionFailed;
use App\Exceptions\TransactionProcessingFailed;
use App\Models\Account;
use App\Models\ScheduledTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    const TRANSACTION_CREDIT_TYPE = 'credit';

    const TRANSACTION_DEBIT_TYPE = 'debit';

    public function createTransaction(
        Account $account,
        Account $creditAccount,
        User $user,
        int $amount,
        int $scheduleId
    )
    {
        DB::beginTransaction();

        try {
            $account->update([
                'amount' => $account->amount - $amount,
            ]);

            $creditAccount->update([
                'amount' => $creditAccount->amount + $amount,
            ]);

            $accountTransaction = $account->transactions()->create([
                'amount' => $amount,
                'type' => self::TRANSACTION_DEBIT_TYPE,
            ]);

            $creditTansaction = $creditAccount->transactions()->create([
                'amount' => $amount,
                'type' => self::TRANSACTION_CREDIT_TYPE,
            ]);

            if (! $accountTransaction || ! $creditTansaction) {
                DB::rollBack();
                $this->dispatchFailedEvent($account, $creditAccount, $user, $amount);

                return false;
            }

            DB::commit();

            if ($scheduleId) {
                ScheduledTransaction::destroy($scheduleId);
            }

            return true;
        } catch (\Throwable $th) {
            // Add audit logs
            DB::rollBack();
            $this->dispatchFailedEvent($account, $creditAccount, $user, $amount);
            throw new TransactionProcessingFailed('Transaction processing failed', Response::HTTP_FORBIDDEN);
        }
    }

    public function createScheduledTransaction(
        Account $account,
        Account $creditAccount,
        float $amount,
        int $userId,
        string $period
    )
    {
        $scheduledTransaction = ScheduledTransaction::create([
            'account_id' => $creditAccount->id,
            'debit_account_id' => $account->id,
            'amount' => $amount,
            'user_id' => $userId,
            'period' => $period,
        ]);

        if (! $scheduledTransaction) {
            /** Add Scheduled Transaction Failed notification */
            return false;
        }

        return $scheduledTransaction;
    }

    private function dispatchFailedEvent(Account $account, Account $creditAccount, User $user, int $amount)
    {
        TransactionFailed::dispatch($account, $creditAccount, $user, $amount);
    }
}
