<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ApproveReservationController extends Controller
{
    public function index()
    {
        return inertia("ApproveReservations", [
            "reservations" => ReservationResource::collection(
                Reservation::query()
                    ->valid(approved: false)
                    ->with(["room.location", "service", "reservedBy"])
                    ->get(),
            ),
        ]);
    }

    public function approve(Reservation $reservation)
    {
        $this->authorize("admin");

        if ($reservation->approve()) {
            session()->flash("message", "تم الموافقة علي المعاد");
        }

        return back();
    }
}
