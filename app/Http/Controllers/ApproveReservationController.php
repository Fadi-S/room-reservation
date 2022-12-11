<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ApproveReservationController extends Controller
{
    public function index()
    {
        $this->authorize("admin");

        return inertia("ApproveReservations", [
            "reservations" => ReservationResource::collection(
                Reservation::query()
                    ->latest("id")
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
            session()->flash("message", "تم الموافقة علي الميعاد");
        }

        return back();
    }

    public function delete(Reservation $reservation)
    {
        $this->authorize("admin");

        if ($reservation->delete()) {
            session()->flash("message", "تم الغاء الحجز");
            session()->flash("type", "warning");
        }

        return back();
    }
}
