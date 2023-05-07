<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalReservationController extends Controller
{
    public function index()
    {
        /* @var User $user */
        $user = Auth::user();

        $reservations = $user
            ->reservations()
            ->valid(approved: false)
            ->with("room.location", "service")
            ->get();

        $reservations->each->setRelation("reservedBy", $user);

        return inertia("PersonalReservations", [
            "reservations" => ReservationResource::collection($reservations),
        ]);
    }
}
