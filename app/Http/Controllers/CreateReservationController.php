<?php

namespace App\Http\Controllers;

use App\Actions\MakeReservation;
use App\Enums\FlashMessageType;
use App\Events\MakeReservationEvent;
use App\Http\Resources\LocationResource;
use App\Models\Location;
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
            "url" => route("reservation.store"),
        ]);
    }

    public function store(Request $request)
    {
        $reservation = MakeReservation::create($request->all());

        if (Auth::user()->isAdmin()) {
            $reservation->approve();

            $this->flash(__("ui.reserved"));
        } else {
            MakeReservationEvent::dispatch($reservation);

            $this->flashPermanently(
                __("ui.waiting_for_approval"),
                FlashMessageType::Warning,
            );
        }

        return redirect()->route("home");
    }
}
