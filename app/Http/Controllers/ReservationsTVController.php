<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Http\Resources\ReservationForTableResource;
use App\Http\Resources\RoomResource;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReservationsTVController extends Controller
{
    public function __invoke(Request $request)
    {
        $date = now();

        $carbonInterval = CarbonInterval::minutes(30);
        $rooms = Room::query()
            ->orderBy("id")
            ->with([
                "location" => fn($query) => $query->with(
                    "rooms:id,location_id",
                ),
            ])
            ->get();

        $hour = $date->copy()->hour;
        if ($hour < 8 || $hour > 23) {
            $hour = 9;
        }

        $timeSteps = collect(
            CarbonPeriod::create(
                $date->copy()->setTime($hour - 1, 0),
                $carbonInterval,
                $date->copy()->setTime(23, 0),
            )->toArray(),
        )->map(
            fn(Carbon $date) => [
                "time" => $date,
                "nextTime" => $date->copy()->add($carbonInterval),
            ],
        );

        $reservations = collect(
            ReservationForTableResource::collection(
                Reservation::query()
                    ->date($date)
                    ->with("room.location", "service")
                    ->get(),
            )->resolve(),
        )->groupBy(["roomId", "startTime"]);

        $reservationsInTime = [];

        foreach ($rooms as $room) {
            $reservationsInTime[$room->id] = [];

            for ($i = 0; $i < $timeSteps->count() - 1; $i++) {
                $time = $timeSteps[$i];
                if (
                    isset($reservations[$room->id]) &&
                    isset(
                        $reservations[$room->id][
                            $time["time"]->format("H:i:s")
                        ],
                    )
                ) {
                    $reservation =
                        $reservations[$room["id"]][
                            $time["time"]->format("H:i:s")
                        ][0];
                    $reservation["empty"] = false;
                    $reservationsInTime[$room["id"]][] = $reservation;

                    $i += $reservation["numberOfTimeSlots"] - 1;
                    continue;
                }

                $reservationsInTime[$room->id][] = [
                    "empty" => true,
                ];
            }
        }

        return view("tv", [
            "reservations" => $reservationsInTime,

            "date" => $date,

            "rooms" => $rooms,

            "timeSteps" => $timeSteps,
        ]);
    }
}
