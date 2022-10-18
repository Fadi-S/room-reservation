<?php

namespace App\Http\Controllers;

use App\Events\ReservationApprovedEvent;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ApproveReservationController extends Controller
{
    public function __invoke(Reservation $reservation)
    {
        $this->authorize("admin");

        $success = $reservation->approve();

        if($success) {
            session()->flash("message", "تم الموافقة علي المعاد");

            ReservationApprovedEvent::dispatch($reservation);
        }

        return back();
    }
}
