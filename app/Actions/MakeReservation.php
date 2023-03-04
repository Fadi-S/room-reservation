<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Rules\RoomAvailableRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class MakeReservation
{
    private static function manipulate($data): Collection
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

    public static function create($data): Reservation
    {
        $data = self::manipulate($data);

        self::validate($data->toArray());

        $reservation = self::getReservationArray($data);

        return Reservation::query()->create($reservation);
    }

    public static function update(Reservation $reservation, $data): Reservation
    {
        $data = self::manipulate($data);

        self::validate($data->toArray(), $reservation->id);

        $reservation->update(self::getReservationArray($data, isCreate: false));

        return $reservation;
    }

    private static function validate(array $data, $ignore = null): array
    {
        $dayOfWeek = $data["dayOfWeek"] ?? null;
        if (isset($data["date"])) {
            $dayOfWeek ??= Carbon::parse($data["date"])->dayOfWeek;
        }

        return Validator::validate($data, [
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
            "isRepeating" => ["boolean"],
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

    /**
     * @param Collection $data
     * @param bool $isCreate
     * @return array
     */
    private static function getReservationArray(
        Collection $data,
        bool $isCreate = true
    ): array {
        $reservation = [
            "is_repeating" => !!$data->get("isRepeating"),
            "service_id" => $data->get("service"),
            "room_id" => $data->get("room"),
            "start" => $data->get("start"),
            "end" => $data->get("end"),
            "description" => $data->get("description"),
        ];

        if ($isCreate) {
            $reservation["user_id"] = auth()->id();
        }

        if ($data->get("isRepeating")) {
            $reservation["day_of_week"] = $data->get("dayOfWeek");
        } else {
            $date = Carbon::parse($data->get("date"));
            $reservation["date"] = $date->format("Y-m-d");

            $reservation["day_of_week"] = $date->dayOfWeek;

            $reservation["stopped_at"] = $date->setTimeFrom($data->get("end"));
        }

        return $reservation;
    }
}
