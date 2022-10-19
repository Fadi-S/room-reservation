<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationForTableResource extends JsonResource
{
    public function toArray($request): array
    {
        /* @var Reservation $this */

        return [
            "id" => $this->id,
            "displayName" => $this->description,
            "isRepeating" => (bool) $this->is_repeating,
            "room" => [
                "id" => $this->room_id,
                "name" => $this->room->fullName,
            ],
            "service" => $this->service->name,
            "color" => $this->service->color,
            "start" => Carbon::parse($this->start)->format("h:i a"),
            "end" => Carbon::parse($this->end)->format("h:i a"),
            "dayOfWeek" => $this->day_of_week,
        ];
    }
}
