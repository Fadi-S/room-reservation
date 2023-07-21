<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalReservationController extends Controller
{
    public function index()
    {
        /* @var User $user */
        $user = Auth::user();

        $reservations = Reservation::query()
            ->whereIn("service_id", $user->services()->pluck("services.id"))
            ->with("room.location", "service", "reservedBy", "pauses.pausedBy")
            ->notStopped(now())
            ->orderBy("day_of_week")
            ->orderBy("start")
            ->get()
            ->map(function (Reservation $reservation) {
                $reservation->approved = (int) $reservation->isApproved();
                return $reservation;
            })
            ->groupBy("approved");

        return inertia("PersonalReservations", [
            "pendingReservations" => ReservationResource::collection(
                ($reservations[0] ?? collect())->sortByDesc("id"),
            ),
            "approvedReservations" => ReservationResource::collection(
                $reservations[1] ?? [],
            ),
        ]);
    }
}
