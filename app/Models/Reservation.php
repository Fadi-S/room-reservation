<?php

namespace App\Models;

use App\Queries\ReservationQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;

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

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public static function query(): ReservationQuery
    {
        return parent::query();
    }

    #[Pure]
    public function newEloquentBuilder($query): ReservationQuery {
        return new ReservationQuery($query);
    }

    public function approve() : bool
    {
        if($this->approved_at != null) return false;

        $this->approved_at = now();
        $this->approved_by_id = Auth::id();

        return $this->save();
    }

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
