<?php

namespace App\Http\Resources;

use App\Models\Pause;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PauseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* @var Pause $this */

        return [
            "date" => [
                "formatted" => $this->date->format("d/m/Y"),
                "date" => $this->date->format("Y-m-d"),
            ],
            "pausedBy" => $this->when(
                $this->relationLoaded("pausedBy"),
                fn() => UserResource::make($this->pausedBy),
            ),
            "reservation" => $this->when(
                $this->relationLoaded("reservation"),
                fn() => ReservationResource::make($this->reservation),
            ),
        ];
    }
}
