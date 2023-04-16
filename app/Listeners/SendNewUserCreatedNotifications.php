<?php

namespace App\Listeners;

use App\Events\NewUserCreated;
use App\Notifications\NewUserCreated as NotificationsNewUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewUserCreatedNotifications implements ShouldQueue
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
    public function handle(NewUserCreated $event)
    {
        $event->user->notify(new NotificationsNewUserCreated($event->user));
    }
}
