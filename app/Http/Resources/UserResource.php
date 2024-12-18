<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        /* @var User $this */

        return [
            "key" => $this->username,
            "id" => $this->id,
            "isAdmin" => $this->isAdmin(),
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "services" => $this->when(
                $this->relationLoaded("services"),
                fn() => ServiceResource::collection($this->services),
            ),
            "links" => [
                "edit" => route("users.edit", $this),
                "delete" => route("users.destroy", $this),
            ],
        ];
    }
}
