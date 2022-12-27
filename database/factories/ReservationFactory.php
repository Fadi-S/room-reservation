<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            "description" => $this->faker->text(),
            "start" => ($start = $this->faker->randomElement([
                "08:00",
                "09:00",
                "10:00",
                "11:00",
                "12:00",
                "13:00",
                "14:00",
                "15:00",
                "16:00",
                "17:00",
                "18:00",
                "19:00",
                "20:00",
                "21:00",
            ])),
            "end" => Carbon::parse($start)
                ->addHours(2)
                ->format("H:i"),
            "is_repeating" => ($repeating = $this->faker->boolean()),
            "date" => ($date = !$repeating
                ? $this->faker
                    ->dateTimeBetween("now", "1 year")
                    ->format("Y-m-d")
                : null),
            "day_of_week" => $date
                ? Carbon::parse($date)->dayOfWeek
                : $this->faker->randomElement(range(0, 6)),
            "stopped_at" => $date ? Carbon::parse($date)->addDay() : null,
            "approved_at" => ($approvedAt = $this->faker
                ->optional()
                ->dateTimeBetween("now", "+1 week")),
            "service_id" => Service::inRandomOrder()->first()->id,
            "room_id" => Room::inRandomOrder()->first()->id,
            "user_id" => User::factory(),
            "approved_by_id" => $approvedAt ? User::factory()->admin() : null,
        ];
    }

    public function approved(): static
    {
        return $this->state(function () {
            return [
                "approved_at" => now(),
                "approved_by_id" => User::factory()->admin(),
            ];
        });
    }

    public function notApproved(): self
    {
        return $this->state(function () {
            return [
                "approved_at" => null,
                "approved_by_id" => null,
            ];
        });
    }
}
