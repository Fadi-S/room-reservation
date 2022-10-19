<?php

namespace App\Http\Resources;

use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        /* @var Room $this */

        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description ?? "",
            "location" => $this->when(
                $this->relationLoaded("location"),
                fn() => LocationResource::make($this->location),
            ),
        ];
    }
}
