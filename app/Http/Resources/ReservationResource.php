<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray($request): array
    {
        /* @var Reservation $this */

        return [
            "id" => $this->id,
            "description" => $this->description,
            "name" => $this->when(
                $this->relationLoaded("service"),
                fn() => $this->description . " " . $this->service->name,
            ),
            "start" => $this->start->translatedFormat("h:i a"),
            "end" => $this->end->translatedFormat("h:i a"),
            "date" => $this->when(
                !$this->is_repeating,
                fn() => $this->date->translatedFormat("d F"),
            ),
            "dayName" => $this->dayOfWeekName,
            "isRepeating" => (bool) $this->is_repeating,
            "room" => $this->when(
                $this->relationLoaded("room"),
                fn() => RoomResource::make($this->room),
            ),
            "service" => $this->when(
                $this->relationLoaded("service"),
                fn() => ServiceResource::make($this->service),
            ),
            "reservedBy" => $this->when(
                $this->relationLoaded("reservedBy"),
                fn() => UserResource::make($this->reservedBy),
            ),
            "created_at" => $this->created_at->format("Y-m-d h:i:s a"),
            "links" => [
                "approve" => $this->when(
                    !$this->isApproved(),
                    route("reservation.approve", $this),
                ),
                "delete" => route("reservation.delete", $this),
            ],
        ];
    }
}
