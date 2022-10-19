<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Http\Resources\ReservationForTableResource;
use App\Models\Location;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $reservations = Reservation::query()
            ->validBetween(
                now()
                    ->previous(1)
                    ->startOfDay(),
                now()
                    ->previous(1)
                    ->addDays(6)
                    ->endOfDay(),
            )
            ->with(["room.location", "service:id,name,color"])
            ->get();

        $locations = LocationResource::collection(
            Location::with("rooms")->get(),
        );

        return inertia("Home", [
            "reservations" => collect(
                ReservationForTableResource::collection(
                    $reservations,
                )->resolve(),
            )->groupBy("dayOfWeek"),

            "locations" => $locations,
        ]);
    }
}
