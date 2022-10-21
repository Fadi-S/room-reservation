<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        /* @var Service $this */

        return [
            "id" => $this->id,
            "name" => $this->name,
            "color" => $this->color,
        ];
    }
}
