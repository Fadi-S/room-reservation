<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

class ReservationQuery extends Builder
{
    public function approved(): static
    {
        return $this->where("approved_at", "<>", null);
    }

    public function notStopped(): static
    {
        return $this->where(function ($q) {
            $q->where("stopped_at", "<", now()->format("Y-m-d H:i:s"))->orWhere(
                "stopped_at",
                "=",
                null,
            );
        });
    }

    public function repeated($isRepeated = true): static
    {
        return $this->where("repeated", "=", $isRepeated);
    }

    public function valid(): static
    {
        return $this->approved()
            ->notStopped()
            ->where(function ($query) {
                $query->where("date", "=", null)->orWhereBetween("date", [
                    now()
                        ->previous(0)
                        ->format("Y-m-d"),
                    now()
                        ->previous(0)
                        ->addDays(6)
                        ->format("Y-m-d"),
                ]);
            });
    }
}
