<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $dates = [
        "date",
        "approved_at",
        "stopped_at",
    ];

    protected $casts = [
        "is_repeating" => "boolean",

    ];

    public function room() : BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function service() : BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function reservedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function approvedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, "approved_by_id");
    }
}
