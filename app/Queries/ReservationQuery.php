<?php

namespace App\Queries;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ReservationQuery extends Builder
{
    public function approved(bool $approved = true): static
    {
        return $this->where("approved_at", $approved ? "<>" : "=", null);
    }

    public function notStopped($date = null): static
    {
        $date ??= now();

        return $this->where(function (self $q) use ($date) {
            $q->where(
                "stopped_at",
                ">=",
                $date->format("Y-m-d H:i:s"),
            )->orWhereNull("stopped_at");
        });
    }

    public function forRoom($room): static
    {
        return $this->where("room_id", "=", $room);
    }

    public function forDay($day): static
    {
        return $this->where("day_of_week", "=", $day);
    }

    public function overlapping($start, $end): static
    {
        return $this->where(function ($query) use ($start, $end) {
            $query
                // If new event includes the old event, or they are overlapping from the start
                ->where(
                    fn($query) => $query
                        ->where("start", ">=", $start)
                        ->where("start", "<", $end),
                )

                // If new event includes the old event, or they are overlapping from the end
                ->orWhere(
                    fn($query) => $query
                        ->where("end", ">", $start)
                        ->where("end", "<", $end),
                )

                // If new event is inside of current event
                ->orWhere(
                    fn($query) => $query
                        ->where("start", "<=", $start)
                        ->where("end", ">=", $end),
                );
        });
    }

    public function repeated($isRepeated = true): static
    {
        return $this->where("is_repeating", "=", $isRepeated);
    }

    public function valid($date = null, $approved = true): static
    {
        return $this->approved($approved)->notStopped($date);
    }

    public function validBetween($start, $end): static
    {
        return $this->valid($start)->where(function (self $query) use (
            $start,
            $end
        ) {
            $query
                ->whereNull("date")
                ->orWhere("date", [
                    $start->format("Y-m-d"),
                    $end->format("Y-m-d"),
                ]);
        });
    }

    public function date(Carbon $date): static
    {
        return $this->validBetween(
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay(),
        )->forDay($date->dayOfWeek);
    }
}
