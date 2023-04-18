<?php

namespace App\Listeners;

use App\Events\NewTransactionCreated;
use App\Events\TransactionFailed;
use App\Notifications\NewTransactionCreated as NotificationsNewTransactionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class SendNewTransactionCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(NewTransactionCreated $event)
    {
        $event->transaction->user->notify(new NotificationsNewTransactionCreated($event->transaction));
    }

    /**
     * Handle a job failure.
     */
    public function failed(NewTransactionCreated $event, Throwable $exception): void
    {
        //logger('transaction failed');
        //auth()->user->notify(new SendTransactionFailedNotifications(auth()->user()));
    }
}
