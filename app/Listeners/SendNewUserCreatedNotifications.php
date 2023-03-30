<?php

namespace App\Listeners;

use App\Events\NewUserCreated;
use App\Notifications\NewUserCreated as NotificationsNewUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Events\NewUserCreated  $event
     * @return void
     */
    public function handle(NewUserCreated $event)
    {
        $event->user->notify(new NotificationsNewUserCreated($event->user));
    }
}
