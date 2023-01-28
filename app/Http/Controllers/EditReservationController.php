<?php

namespace App\Http\Controllers;

use App\Actions\MakeReservation;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditReservationController extends Controller
{
    public function edit(Reservation $reservation)
    {
        $reservation->load("room");
        $this->authorize("admin");

        return inertia("ReservationForm", [
            "services" => Auth::user()
                ->allowedServices()
                ->pluck("services.name", "services.id"),
            "reservation" => [
                "description" => $reservation->description,
                "service_id" => $reservation->service_id,
                "room_id" => $reservation->room_id,
                "location_id" => $reservation->room->location_id,
                "start" => $reservation->start,
                "end" => $reservation->end,
                "date" => $reservation->date?->format("Y-m-d"),
                "day_of_week" => $reservation->day_of_week,
                "is_repeating" => $reservation->is_repeating,
            ],
            "url" => route("reservation.update", $reservation),
            "deleteUrl" => route("reservation.stop", $reservation),
            "locations" => LocationResource::collection(
                Location::with("rooms")->get(),
            ),
        ]);
    }

    public function update(Reservation $reservation, Request $request)
    {
        $this->authorize("admin");

        MakeReservation::update($reservation, $request->all());

        $this->flash(__("ui.reservation_edited"));

        return back();
    }

    public function destroy(Reservation $reservation)
    {
        $date = $reservation->nextOccurrence;

        $reservation->stopped_at = now();

        $reservation->save();

        $this->flash(__("ui.reservation_stopped"));

        return redirect()->route("table", [
            "day" => $date?->format("Y-m-d"),
        ]);
    }
}
