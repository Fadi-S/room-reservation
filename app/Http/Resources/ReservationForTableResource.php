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
            "roomId" => $this->room_id,
            "room" => $this->room->fullName,
            "service" => $this->service->name,
            "color" => $this->service->color,
            "start" => [
                "time" => $this->start,
                "formatted" => Carbon::parse($this->start)->translatedFormat(
                    "h:i a",
                ),
            ],
            "end" => [
                "time" => $this->end,
                "formatted" => Carbon::parse($this->end)->translatedFormat(
                    "h:i a",
                ),
            ],
            "dayOfWeek" => $this->day_of_week,
        ];
    }
}
