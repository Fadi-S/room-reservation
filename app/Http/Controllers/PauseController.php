<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use App\Models\Pause;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PauseController extends Controller
{
    public function store(Reservation $reservation, Request $request)
    {
        $this->authorize("pause", $reservation);

        $request->validate([
            "date" => ["date", "after_or_equal:" . now()->format("Y-m-d")],
        ]);

        $date = $request->date("date");
        if ($date->dayOfWeek != $reservation->day_of_week) {
            return back()->withErrors([
                "date" => __("validation.day_of_week"),
            ]);
        }

        $pause = Pause::updateOrCreate([
            "paused_by_id" => \Auth::id(),
            "reservation_id" => $reservation->id,
            "date" => $date,
        ]);

        $this->flash(
            __("ui.reservation_paused", [
                "date" => $pause->date->format("d/m/Y"),
            ]),
        );

        return back();
    }

    public function destroy(Pause $pause)
    {
        $pause->load("reservation");

        $this->authorize("pause", $pause->reservation);

        $exist = Reservation::query()
            ->forRoom($pause->reservation->room_id)
            ->forDay($pause->date->dayOfWeek)
            ->valid($pause->date)
            ->overlapping($pause->reservation->start, $pause->reservation->end)
            ->exists();

        if ($exist) {
            $this->flash("This room is reserved!", FlashMessageType::Error);

            return back();
        }

        $pause->delete();

        $this->flash("Reservation not paused anymore");

        return back();
    }
}
