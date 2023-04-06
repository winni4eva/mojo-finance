<?php

namespace App\Listeners;

use App\Events\NewTransactionCreated;
use App\Notifications\NewTransactionCreated as NotificationsNewTransactionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Events\NewTransactionCreated  $event
     * @return void
     */
    public function handle(NewTransactionCreated $event)
    {
        $event->user->notify(new NotificationsNewTransactionCreated($event->user));
    }
}
