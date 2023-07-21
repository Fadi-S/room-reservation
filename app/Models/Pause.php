<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pause extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        "date" => "date",
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function pausedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "paused_by_id");
    }
}
