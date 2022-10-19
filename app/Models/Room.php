<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function fullName(): Attribute
    {
        return new Attribute(function ($value, $attributes) {
            $name = $this->location->name;

            if ($this->description) {
                $name .= " $this->description";
            }

            if ($this->name) {
                $name .= " $this->name";
            }

            return $name;
        });
    }
}
