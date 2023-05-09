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
    public function __construct(private readonly MakeReservation $repository)
    {
    }

    public function edit(Reservation $reservation)
    {
        $reservation->load("room");
        $this->authorize("update", $reservation);

        $isInSummer = $this->repository->isSummer();

        $user = Auth::user();

        $repeating = $reservation->is_repeating;
        if ($repeating && $reservation->stopped_at && now()->lt($reservation->stopped_at)) {
            $repeating = "summer";
        }

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
                "is_repeating" => $repeating,
            ],
            "url" => route("reservation.update", $reservation),
            "deleteUrl" => $user->isAdmin() ? route("reservation.stop", $reservation) : null,
            "locations" => LocationResource::collection(
                Location::with("rooms")->get(),
            ),
            "isInSummer" => $isInSummer,
        ]);
    }

    public function update(Reservation $reservation, Request $request)
    {
        $this->authorize("update", $reservation);

        $this->repository->update($reservation, $request->all());

        $this->flash(__("ui.reservation_edited"));

        return back();
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize("delete", $reservation);

        $date = $reservation->nextOccurrence;

        $reservation->stopped_at = now();

        $reservation->save();

        $this->flash(__("ui.reservation_stopped"));

        return redirect()->route("table", [
            "day" => $date?->format("Y-m-d"),
        ]);
    }
}
