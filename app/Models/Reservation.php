<?php

namespace App\Models;

use App\Events\ReservationApprovedEvent;
use App\Queries\ReservationQuery;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;

class Reservation extends Model
{
    use HasFactory;

    protected $dates = ["date", "approved_at", "stopped_at"];

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

    public function isApproved(): bool
    {
        return !!$this->approved_at;
    }

    public function dayOfWeekName(): Attribute
    {
        return new Attribute(
            get: fn() => collect(
                CarbonPeriod::between(
                    now()
                        ->startOfWeek()
                        ->subDay(),
                    now()
                        ->endOfWeek()
                        ->subDay(),
                )->toArray(),
            )->map->translatedFormat("l")[$this->day_of_week],
        );
    }

    public function numberOfTimeSlotsIn(CarbonInterval $interval): int
    {
        $diff = (new \DateTime($this->end))->diff(new \DateTime($this->start));

        return ($diff->h * 60 + $diff->i) / $interval->minutes;
    }

    public function nextOccurrence(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (now()->gte($this->stopped_at)) {
                    return null;
                }

                if ($this->is_repeating) {
                    return now()->next($this->day_of_week);
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

        $this->approved_at = now();
        $this->approved_by_id = Auth::id();

        $saved = $this->save();

        if ($saved) {
            ReservationApprovedEvent::dispatch($this);
        }

        return $saved;
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
