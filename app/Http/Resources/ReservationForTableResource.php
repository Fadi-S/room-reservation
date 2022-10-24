<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
            "color" => [
                "bg" => $this->service->color->lighten(40)->get(),
                "text" => $this->service->color->darken(80)->get(),
            ],
            "numberOfTimeSlots" => $this->numberOfTimeSlotsIn(
                CarbonInterval::minutes(30),
            ),
            "startTime" => $this->start,
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
