<?php

namespace App\Http\Controllers;

use App\Events\MakeReservationEvent;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\Reservation;
use App\Rules\RoomAvailableRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateReservationController extends Controller
{
    public function create()
    {
        return inertia("ReservationForm", [
            "services" => Auth::user()
                ->allowedServices()
                ->pluck("services.name", "services.id"),
            "locations" => LocationResource::collection(
                Location::with("rooms")->get(),
            ),
        ]);
    }

    public function store(Request $request)
    {
        if (is_array($request->get("start"))) {
            $start = $request->get("start");
            $end = $request->get("end");
            $request->request->set(
                "start",
                Carbon::create(
                    hour: $start["hours"],
                    minute: $start["minutes"],
                )->format("H:i"),
            );
            $request->request->set(
                "end",
                Carbon::create(
                    hour: $end["hours"],
                    minute: $end["minutes"],
                )->format("H:i"),
            );
        }

        if (!$request->boolean("isRepeating")) {
            $request->request->set(
                "date",
                $request
                    ->date("date")
                    ->setTimeFrom(Carbon::parse($request->get("start"))),
            );
        }

        $request->validate([
            "service" => ["required", "exists:services,id"],
            "room" => ["required", "exists:rooms,id"],
            "description" => ["nullable", "max:250"],
            "date" => [
                "nullable",
                "required_if:isRepeating,false",
                "date",
                "after_or_equal:" . now()->format("Y-m-d h:i a"),
            ],
            "dayOfWeek" => ["required_if:isRepeating,true", "between:0,6"],
            "isRepeating" => ["boolean"],
            "start" => [
                "required",
                "date_format:H:i",
                new RoomAvailableRule(
                    $request->get("room"),
                    $request->date("date"),
                    $request->get("dayOfWeek"),
                    $request->get("start"),
                    $request->get("end"),
                ),
            ],
            "end" => ["required", "date_format:H:i", "after:start"],
        ]);

        $reservation = [
            "is_repeating" => $request->boolean("isRepeating"),
            "user_id" => \auth()->id(),
            "service_id" => $request->get("service"),
            "room_id" => $request->get("room"),
            "start" => $request->get("start"),
            "end" => $request->get("end"),
            "description" => $request->get("description"),
        ];

        if ($request->boolean("isRepeating")) {
            $reservation["day_of_week"] = $request->integer("dayOfWeek");
        } else {
            $date = $request->date("date");
            $reservation["date"] = $date->format("Y-m-d");

            $reservation["day_of_week"] = $date->dayOfWeek;

            $reservation["stopped_at"] = $date
                ->setTimeFrom($request->date("end", "H:i"))
                ->addMinute();
        }

        $reservation = Reservation::query()->create($reservation);

        if (Auth::user()->isAdmin()) {
            $reservation->approve();
            session()->flash("message", "تم الحجز");
        } else {
            MakeReservationEvent::dispatch($reservation);

            session()->flash("message", "حفظ الحجز, في انتظار التأكيد");
        }

        return redirect()->route("home");
    }
}
