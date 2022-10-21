<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray($request): array
    {
        /* @var Reservation $this */

        return [
            "id" => $this->id,
            "displayName" => $this->description,
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
            ],
        ];
    }
}
