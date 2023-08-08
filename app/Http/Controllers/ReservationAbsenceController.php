<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationAbsenceController extends Controller
{
    public function store(Reservation $reservation, Request $request)
    {
        $this->authorize("admin");

        $reservation->load("service");

        $reservation->absences()->create([
            "user_id" => auth()->id(),
            "date" => $request->date("date"),
        ]);

        $this->flash(
            __("ui.reservation_absence", [
                "reservation" =>
                    $reservation->description .
                    " " .
                    $reservation->service->name,
            ]),
            FlashMessageType::Warning,
        );

        return back();
    }

    public function delete(Reservation $reservation, Request $request)
    {
        $this->authorize("admin");
        $reservation->load("service");

        $this->flash(
            __("ui.reservation_absence_cancel", [
                "reservation" =>
                    $reservation->description .
                    " " .
                    $reservation->service->name,
            ]),
            FlashMessageType::Info,
        );

        $reservation
            ->absences()
            ->where("date", "=", $request->date("date"))
            ->delete();

        return back();
    }
}
