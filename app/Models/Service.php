<?php

namespace App\Models;

use App\Helpers\Color;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function color(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                return Color::make($value);
            },
        );
    }
}
