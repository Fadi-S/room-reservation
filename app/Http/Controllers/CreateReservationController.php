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
    public function __construct(private readonly MakeReservation $repository)
    {
    }

    public function create()
    {
        $isInSummer = $this->repository->isSummer();

        return inertia("ReservationForm", [
            "services" => Auth::user()
                ->allowedServices()
                ->pluck("services.name", "services.id"),
            "locations" => LocationResource::collection(
                Location::with("rooms")->get(),
            ),
            "url" => route("reservation.store"),
            "isInSummer" => $isInSummer,
        ]);
    }

    public function store(Request $request)
    {
        $reservation = $this->repository->create($request->all());

        if (Auth::user()->isAdmin()) {
            $reservation->approve();

            $this->flash(__("ui.reserved"));

            return redirect()->route("home", [
                "day" => $reservation->nextOccurrence?->format("Y-m-d"),
            ]);
        }

        MakeReservationEvent::dispatch($reservation);

        $this->flashPermanently(
            __("ui.waiting_for_approval"),
            FlashMessageType::Warning,
        );

        return redirect()->route("reservations.personal");
    }

}
