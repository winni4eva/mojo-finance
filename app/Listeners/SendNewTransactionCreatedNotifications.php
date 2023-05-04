<?php

namespace App\Listeners;

use App\Events\NewTransactionCreated;
use App\Notifications\NewTransactionCreated as NotificationsNewTransactionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $event->transaction->account->user->notify(new NotificationsNewTransactionCreated($event->transaction));
    }
}
