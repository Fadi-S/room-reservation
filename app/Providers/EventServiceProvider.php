<?php

namespace App\Providers;

use App\Events\MakeReservationEvent;
use App\Events\ReservationApprovedEvent;
use App\Events\ReservationUpdatedEvent;
use App\Listeners\SendReservationApprovedNotification;
use App\Listeners\SendReservationCreatedNotification;
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
        Registered::class => [SendEmailVerificationNotification::class],

        MakeReservationEvent::class => [
            SendReservationCreatedNotification::class,
        ],

        ReservationApprovedEvent::class => [
            ReservationUpdatedEvent::class,
            SendReservationApprovedNotification::class,
        ],
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
