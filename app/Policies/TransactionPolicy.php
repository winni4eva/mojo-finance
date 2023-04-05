<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\HttpFoundation\Response;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account $account
     * @param  \App\Models\Account $creditAccount
     * @param float $depositAmount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Account $account, Account $creditAccount, float $depositAmount)
    {
        if ($user->id != $account->user_id) {
            return $this->deny($this->getDenyMessage(''), Response::HTTP_FORBIDDEN);
        }

        if (! $creditAccount) {
            return $this->deny($this->getDenyMessage('ACCOUNT_DOES_NOT_EXIST'), Response::HTTP_FORBIDDEN);
        }

        if ($account->id == $creditAccount->id) {
            return $this->deny($this->getDenyMessage('ACCOUNTS_ARE_THE_SAME'), Response::HTTP_FORBIDDEN);
        }

        if (($depositAmount / 100) > $account->amount) {
            return $this->deny($this->getDenyMessage('INSUFFICIENT_ACCOUNT_BALANCE'), Response::HTTP_FORBIDDEN);
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Transaction $transaction)
    {
        //
    }

    private function getDenyMessage(string $messageKey): string
    {
        return match ($messageKey) {
            'ACCOUNT_DOES_NOT_EXIST' => 'Credit account does not exist',
            'ACCOUNTS_ARE_THE_SAME' => 'Debit and credit accounts are the same',
            'INSUFFICIENT_ACCOUNT_BALANCE' => 'You do not have sufficient balance to perform this transaction',
            default => 'You are not authorized to make this request'
        };
    }
}
