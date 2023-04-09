<?php

namespace App\Listeners;

use App\Events\TransactionFailed;
use App\Notifications\TransactionFailed as NotificationsTransactionFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTransactionFailedNotifications
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
     * @param  \App\Events\TransactionFailed  $event
     * @return void
     */
    public function handle(TransactionFailed $event)
    {
         $event->account->user->notify(new NotificationsTransactionFailed($event->account, $event->creditAccount, $event->userId, $event->amount));
    }
}
