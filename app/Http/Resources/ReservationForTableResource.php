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
            "displayName" => "$this->description",
            "isRepeating" => (bool) $this->is_repeating,
            "roomId" => $this->room_id,
            "room" => $this->room->name,
            "service" => $this->service->name,
            "color" => [
                "bg" => $this->service->color->lighten(80)->get(),
                "text" => $this->service->color->darken(90)->get(),
                "original" => $this->service->color->get(),
            ],
            "numberOfTimeSlots" => $this->numberOfTimeSlotsIn(
                CarbonInterval::minutes(30),
            ),
            "startTime" => $this->start,
            "start" => [
                "time" => $this->start,
                "formatted" => Carbon::parse($this->start)->translatedFormat(
                    "h:i",
                ),
            ],
            "end" => [
                "time" => $this->end,
                "formatted" => Carbon::parse($this->end)->translatedFormat(
                    "h:i",
                ),
            ],
            "dayOfWeek" => $this->day_of_week,
            "edit" => route("reservations.edit", $this),
            "absence" => route("reservation.absence", $this),
            "isAbsent" => $this->when(
                $this->relationLoaded("absences"),
                fn() => $this->absences->isNotEmpty(),
            ),
        ];
    }
}
