<?php

namespace App\Listeners;

use App\Events\ReservationApprovedEvent;
use App\Mail\SendReservationApprovedMail;
use App\Mail\SendReservationMadeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationApprovedNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle(ReservationApprovedEvent $event)
    {
        Mail::to($event->reservationModel->reservedBy->email)->send(
            new SendReservationApprovedMail($event->reservationModel),
        );
    }
}
