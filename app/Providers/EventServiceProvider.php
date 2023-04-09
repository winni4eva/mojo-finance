<?php

namespace App\Providers;

use App\Events\NewTransactionCreated;
use App\Events\NewUserCreated;
use App\Events\TransactionFailed;
use App\Listeners\SendNewTransactionCreatedNotifications;
use App\Listeners\SendNewUserCreatedNotifications;
use App\Listeners\SendTransactionFailedNotifications;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewUserCreated::class => [
            SendNewUserCreatedNotifications::class,
        ],
        NewTransactionCreated::class => [
            SendNewTransactionCreatedNotifications::class
        ],
        TransactionFailed::class => [
            SendTransactionFailedNotifications::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
