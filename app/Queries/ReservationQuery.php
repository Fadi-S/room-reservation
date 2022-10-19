<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

class ReservationQuery extends Builder
{
    public function approved(): static
    {
        return $this->where("approved_at", "<>", null);
    }

    public function notStopped($date = null): static
    {
        $date ??= now();

        return $this->where(function ($q) use ($date) {
            $q->where("stopped_at", ">", $date->format("Y-m-d H:i:s"))->orWhere(
                "stopped_at",
                "=",
                null,
            );
        });
    }

    public function forRoom($room): static
    {
        return $this->where("room_id", "=", $room);
    }

    public function overlapping($start, $end): static
    {
        return $this->where(function ($query) use ($start, $end) {
            $query
                ->where(function ($query) use ($start, $end) {
                    $query
                        ->where("start", ">", $start)
                        ->where("start", "<", $end);
                })

                ->orWhere(function ($query) use ($start, $end) {
                    $query->where("end", ">", $start)->where("end", "<", $end);
                })

                ->orWhere(function ($query) use ($start, $end) {
                    $query
                        ->where("start", "<", $start)
                        ->where("end", ">", $end);
                });
        });
    }

    public function repeated($isRepeated = true): static
    {
        return $this->where("repeated", "=", $isRepeated);
    }

    public function valid($date = null): static
    {
        return $this->approved()->notStopped($date);
    }

    public function validBetween($start, $end)
    {
        return $this->valid()->where(function ($query) use ($start, $end) {
            $query
                ->where("date", "=", null)
                ->orWhereBetween("date", [$start, $end]);
        });
    }
}
