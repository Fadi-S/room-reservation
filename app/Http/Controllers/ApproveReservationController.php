<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Rules\RoomAvailableRule;

class ApproveReservationController extends Controller
{
    public function index()
    {
        $this->authorize("admin");

        return inertia("ApproveReservations", [
            "reservations" => ReservationResource::collection(
                Reservation::query()
                    ->latest("id")
                    ->valid(approved: false)
                    ->with(["room.location", "service", "reservedBy"])
                    ->get(),
            ),
        ]);
    }
    public function approve(Reservation $reservation)
    {
        $this->authorize("admin");

        $roomAvailability = RoomAvailableRule::fromReservation($reservation);

        if (!$roomAvailability->isAvailable()) {
            $this->flash($roomAvailability->message(), FlashMessageType::Error);

            return back();
        }

        if ($reservation->approve()) {
            $this->flash(__("ui.approved"));
        }

        return back();
    }

    public function delete(Reservation $reservation)
    {
        $this->authorize("forceDelete", $reservation);

        if ($reservation->delete()) {
            $this->flash(
                __("ui.reservation_deleted"),
                FlashMessageType::Warning,
            );
        }

        return back();
    }
}
