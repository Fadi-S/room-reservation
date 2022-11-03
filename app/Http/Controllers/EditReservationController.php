<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditReservationController extends Controller
{
    public function edit(Reservation $reservation)
    {
        $this->authorize("admin");

        return inertia("ReservationForm", [
            "services" => Auth::user()
                ->allowedServices()
                ->pluck("services.name", "services.id"),
            "reservation" => [

            ],
            "locations" => LocationResource::collection(
                Location::with("rooms")->get(),
            ),
        ]);
    }

    public function update(Reservation $reservation, Request $request)
    {
        $this->authorize("admin");


    }

    public function destroy(Reservation $reservation)
    {
        $reservation->stopped_at = now();

        $reservation->save();
    }
}
