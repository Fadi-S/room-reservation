<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Http\Resources\ReservationForTableResource;
use App\Models\Location;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->has("day")) {
            return redirect()->route("home", [
                "day" => now()->format("Y-m-d"),
            ]);
        }

        $date = $request->date("day");

        return inertia("Home", [
            "reservations" => fn() => collect(
                ReservationForTableResource::collection(
                    Reservation::query()
                        ->with([
                            "absences" => fn($query) => $query->where(
                                "date",
                                "=",
                                $date,
                            ),
                        ])
                        ->orderBy("start")
                        ->date($date)
                        ->with(["room.location", "service:id,name,color"])
                        ->get(),
                )
                    ->additional(["date" => $date])
                    ->resolve(),
            )->groupBy("roomId"),

            "locations" => fn() => LocationResource::collection(
                Location::with("rooms")
                    ->whereHas(
                        "reservations",
                        fn($query) => $query->date($date),
                    )
                    ->orderBy("id")
                    ->get(),
            ),

            "days" => fn() => collect(
                CarbonPeriod::until(now()->addWeek())->toArray(),
            )->map(
                fn(Carbon $date) => [
                    "id" => $date->format("Y-m-d"),
                    "name" => $date->translatedFormat("l d"),
                ],
            ),
        ]);
    }
}
