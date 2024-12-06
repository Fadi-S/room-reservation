<?php

namespace App\Listeners;

use App\Events\MakeReservationEvent;
use App\Mail\SendReservationMadeMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedNotification
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
    public function handle(MakeReservationEvent $event)
    {
        if ($event->reservationModel->isEligibleForAutoApprove()) {
            return;
        }

        $emails = User::whereIsAdmin(true)->pluck("email");

        foreach ($emails as $email) {
            Mail::to($email)->send(
                new SendReservationMadeMail($event->reservationModel),
            );
        }
    }
}
