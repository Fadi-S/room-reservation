<?php

namespace App\Models;

use App\Events\ReservationApprovedEvent;
use App\Queries\ReservationQuery;
use App\Rules\RoomAvailableRule;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;

class Reservation extends Model
{
    use HasFactory;

    protected $casts = [
        "is_repeating" => "boolean",
        "date" => "date",
        "approved_at" => "datetime",
        "stopped_at" => "datetime",
    ];

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public static function query(): ReservationQuery
    {
        return parent::query();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            $reservation->start = Carbon::parse($reservation->start)
                ->setSecond(0)
                ->format("H:i:s");

            $reservation->end = Carbon::parse($reservation->end)
                ->setSecond(0)
                ->format("H:i:s");
        });
    }

    #[Pure]
    public function newEloquentBuilder($query): ReservationQuery {
        return new ReservationQuery($query);
    }

    public function isApproved(): bool
    {
        return !!$this->approved_at;
    }

    public function dayOfWeekName(): Attribute
    {
        return new Attribute(
            get: fn() => collect(
                CarbonPeriod::between(
                    now()->previous(0),
                    now()->next(6),
                )->toArray(),
            )->map->translatedFormat("l")[$this->day_of_week],
        );
    }

    public function numberOfTimeSlotsIn(CarbonInterval $interval): int
    {
        $diff = (new \DateTime($this->end))->diff(new \DateTime($this->start));

        return ($diff->h * 60 + $diff->i) / $interval->minutes;
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function nextOccurrence(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (now()->gte($this->stopped_at ?? "")) {
                    return null;
                }

                if ($this->is_repeating) {
                    return now()->next((int) $this->day_of_week);
                }

                return $this->date;
            },
        );
    }

    public function approve(): bool
    {
        if ($this->approved_at != null) {
            return false;
        }

        $roomAvailability = RoomAvailableRule::fromReservation($this);

        if (!$roomAvailability->isAvailable()) {
            return false;
        }

        $this->approved_at = now();
        $this->approved_by_id = Auth::check() ? Auth::id() : 1;

        $saved = $this->save();

        if ($saved) {
            ReservationApprovedEvent::dispatch($this);
        }

        return $saved;
    }

    public function isEligibleForAutoApprove(): bool
    {
        if (!$this->relationLoaded("room")) {
            $this->load("room");
        }

        return !$this->is_repeating &&
            config("app.allow_auto_approve_for_one_time_reservations") &&
            !in_array(
                $this->room_id,
                config("app.rooms_that_must_be_approved_manually", []),
            ) &&
            !in_array(
                $this->room->location_id,
                config("app.locations_that_must_be_approved_manually", []),
            );
    }

    public function pauses(): HasMany
    {
        return $this->hasMany(Pause::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function reservedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "approved_by_id");
    }
}
