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

        if ($reservation->approve()) {
            session()->flash("message", "تم الموافقة علي المعاد");
        }

        return back();
    }
}
