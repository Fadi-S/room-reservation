<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        /* @var Location $this */
        return [
            "id" => $this->id,
            "name" => $this->name,
            "rooms" => $this->when(
                $this->relationLoaded("rooms"),
                fn() => RoomResource::collection($this->rooms),
            ),
        ];
    }
}
