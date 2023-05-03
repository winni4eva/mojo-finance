<?php

namespace App\Service;

use App\Events\TransactionFailed;
use App\Exceptions\TransactionProcessingFailed;
use App\Models\Account;
use App\Models\ScheduledTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    protected const TRANSACTION_CREDIT_TYPE = 'credit';

    protected const TRANSACTION_DEBIT_TYPE = 'debit';

    public function createTransaction(Account $account, Account $creditAccount, User $user, int $amount, int $scheduleId)
    {
        try {
            DB::beginTransaction();

            $account->update([
                'amount' => $account->amount - $amount,
            ]);

            $creditAccount->update([
                'amount' => $creditAccount->amount + $amount,
            ]);

            $transactions = Transaction::insert([
                [
                    'account_id' => $creditAccount->id,
                    'amount' => $amount,
                    'type' => self::TRANSACTION_CREDIT_TYPE,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'account_id' => $account->id,
                    'amount' => $amount,
                    'type' => self::TRANSACTION_DEBIT_TYPE,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            if (! $transactions) {
                DB::rollBack();
                $this->dispatchFailedEvent($account, $creditAccount, $user, $amount);

                return false;
            }

            DB::commit();

            if ($scheduleId) {
                ScheduledTransaction::destroy($scheduleId);
            }

            return $transactions;
        } catch (\Throwable $th) {
            // Add audit logs
            DB::rollBack();
            $this->dispatchFailedEvent($account, $creditAccount, $user, $amount);
            logger('Message: '.$th->getMessage().' Line: '.$th->getLine().'File: '.$th->getFile());
            throw new TransactionProcessingFailed('Transaction processing failed', Response::HTTP_FORBIDDEN);
        }
    }

    public function createScheduledTransaction(Account $account, int $userId)
    {
        $scheduledTransaction = ScheduledTransaction::create([
            'account_id' => (int) request('credit_account'),
            'debit_account_id' => $account->id,
            'amount' => request('amount'),
            'user_id' => $userId,
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
