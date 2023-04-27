<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Rules\RoomAvailableRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class MakeReservation
{
    private function manipulate($data): Collection
    {
        $data = collect($data);
        if (is_array($data->get("start"))) {
            $start = $data->get("start");
            $end = $data->get("end");
            $data->put(
                "start",
                Carbon::create(
                    hour: $start["hours"],
                    minute: $start["minutes"],
                )->format("H:i"),
            );
            $data->put(
                "end",
                Carbon::create(
                    hour: $end["hours"],
                    minute: $end["minutes"],
                )->format("H:i"),
            );
        }

        if (!$data->get("isRepeating")) {
            $data["date"] = Carbon::parse($data->get("date"))->setTimeFrom(
                Carbon::parse($data->get("start")),
            );
        }

        return $data;
    }

    public function create($data): Reservation
    {
        $data = $this->manipulate($data);

        $this->validate($data->all());

        return Reservation::query()->create(
            array_merge(
                ["user_id" => auth()->id()],
                $this->getReservationArray($data),
            ),
        );
    }

    public function update(Reservation $reservation, $data): Reservation
    {
        $data = $this->manipulate($data);

        $this->validate($data->all(), $reservation->id);

        $reservation->update($this->getReservationArray($data));

        return $reservation;
    }

    public function isSummer(): bool
    {
        return now()->between(
            now()
                ->setMonth(5)
                ->setDay(5)
                ->startOfDay(),
            now()
                ->setMonth(9)
                ->endOfMonth(),
        );
    }

    private function validate(array $data, $ignore = null): void
    {
        $dayOfWeek = $data["dayOfWeek"] ?? null;
        if (isset($data["date"])) {
            $dayOfWeek ??= Carbon::parse($data["date"])->dayOfWeek;
        }

        Validator::validate($data, [
            "service" => ["required", "exists:services,id"],
            "room" => ["required", "exists:rooms,id"],
            "description" => ["nullable", "max:250"],
            "date" => [
                "nullable",
                "required_if:isRepeating,false",
                "date",
                "after_or_equal:" . now()->format("Y-m-d h:i a"),
            ],
            "dayOfWeek" => ["required_if:isRepeating,true", "between:0,6"],
            "isRepeating" => ["in:0,1,false,true,,summer"],
            "start" => [
                "required",
                "date_format:H:i",
                new RoomAvailableRule(
                    $data["room"] ?? null,
                    $data["date"] ?? null,
                    $dayOfWeek,
                    $data["start"] ?? null,
                    $data["end"] ?? null,
                    $ignore,
                ),
            ],
            "end" => ["required", "date_format:H:i", "after:start"],
        ]);
    }

    private function getReservationArray(Collection $data): array
    {
        $reservation = [
            "is_repeating" => $data->get("isRepeating") == true,
            "service_id" => $data->get("service"),
            "room_id" => $data->get("room"),
            "start" => $data->get("start"),
            "end" => $data->get("end"),
            "description" => $data->get("description"),
            "day_of_week" => $data->get("dayOfWeek"),
        ];

        if ($data->get("isRepeating") === true) {
            $reservation["stopped_at"] = null;
        } elseif ($data->get("isRepeating") === "summer" && $this->isSummer()) {
            $reservation["stopped_at"] = now()
                ->month(9)
                ->endOfMonth();
        } else {
            $date = Carbon::parse($data->get("date"));
            $reservation["date"] = $date->format("Y-m-d");

            $reservation["day_of_week"] = $date->dayOfWeek;

            $reservation["stopped_at"] = $date->setTimeFrom($data->get("end"));
        }

        return $reservation;
    }
}
