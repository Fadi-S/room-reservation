<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Location extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function rooms() : HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function reservations() : HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, Room::class);
    }
}
