<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Http\Resources\ReservationForTableResource;
use App\Models\Location;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReservationsTableController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->has("day")) {
            return redirect()->route("table", [
                "day" => now()->format("Y-m-d"),
            ]);
        }

        $date = $request->date("day");

        return inertia("ReservationsTable", [
            "reservations" => fn() => collect(
                ReservationForTableResource::collection(
                    Reservation::query()
                        ->date($date)
                        ->with("room.location", "service")
                        ->get(),
                )->resolve(),
            )->groupBy("roomId"),

            "locations" => fn() => LocationResource::collection(
                Location::query()
                    ->orderBy("id")
                    ->with("rooms")
                    ->get(),
            ),

            "nextWeekDays" => fn() => collect(
                CarbonPeriod::until(now()->addWeek())->toArray(),
            )->map(
                fn(Carbon $date) => [
                    "id" => $date->format("Y-m-d"),
                    "name" => $date->translatedFormat("l d"),
                ],
            ),

            "timeSteps" => fn() => collect(
                CarbonPeriod::create(
                    $date->copy()->setTime(8, 0),
                    CarbonInterval::minutes(30),
                    $date->copy()->setTime(22, 0),
                )->toArray(),
            )->map(
                fn(Carbon $date) => [
                    "id" => $date->format("H:i"),
                    "name" => $date->translatedFormat("h:i a"),
                ],
            ),
        ]);
    }
}
