<?php

use App\Mail\SendReservationMadeMail;
use App\Models\Reservation;
use Carbon\Carbon;
use Database\Seeders\RoomSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\UserSeeder;
use Inertia\Testing\AssertableInertia;

uses()->group("reservation");

beforeEach(function () {
    $this->seed([ServiceSeeder::class, RoomSeeder::class, UserSeeder::class]);
});

test("Visit reservation page", function () {
    login()
        ->get(route("reservation.create"))
        ->assertInertia(
            fn(AssertableInertia $assert) => $assert->component(
                "ReservationForm",
            ),
        )
        ->assertOk();
});

test(
    "Can make reservation for one-time event on a different day and same time and room",
    function () {
        $date = now()->addMonth();
        $existingReservation = Reservation::factory()->create([
            "is_repeating" => false,
            "date" => $date->format("Y-m-d"),
            "day_of_week" => $date->dayOfWeek,
            "start" => "10:00:00",
            "end" => "11:00:00",
        ]);

        // Create a new reservation for the same room and time on a different day
        $newReservationAfter = Reservation::factory()->make([
            "is_repeating" => false,
            "date" => $date
                ->copy()
                ->addWeek()
                ->format("Y-m-d"),
            "start" => "10:00",
            "end" => "11:00",
            "day_of_week" => $date->dayOfWeek,
            "room_id" => $existingReservation->room_id,
        ]);

        $newReservationBefore = Reservation::factory()->make([
            "is_repeating" => false,
            "date" => $date
                ->copy()
                ->subWeek()
                ->format("Y-m-d"),
            "start" => "10:00",
            "end" => "11:00",
            "day_of_week" => $date->dayOfWeek,
            "room_id" => $existingReservation->room_id,
        ]);

        foreach (
            [$newReservationAfter, $newReservationBefore]
            as $newReservation
        ) {
            login()
                ->post(route("reservation.store"), [
                    "isRepeating" => $newReservation["is_repeating"],
                    "service" => $newReservation["service_id"],
                    "room" => $newReservation["room_id"],
                    "description" => $newReservation["description"],
                    "date" => $newReservation["date"], // use the one-time field
                    "dayOfWeek" => $newReservation["day_of_week"], // not used for one-time
                    "start" => $newReservation["start"],
                    "end" => $newReservation["end"],
                ])
                ->assertSessionDoesntHaveErrors()
                ->assertSessionHas("message");
        }

        $this->assertEquals(3, Reservation::count());
    },
);

test("Can make reservation", function () {
    $reservation = Reservation::factory()->make();

    Mail::fake();

    login()
        ->post(route("reservation.store"), [
            "isRepeating" => $reservation["is_repeating"],
            "service" => $reservation["service_id"],
            "room" => $reservation["room_id"],
            "description" => $reservation["description"],
            "date" => $reservation["date"],
            "dayOfWeek" => $reservation["day_of_week"],
            "start" => $reservation["start"],
            "end" => $reservation["end"],
        ])
        ->assertSessionDoesntHaveErrors()
        ->assertSessionHas("message");

    Mail::assertSent(SendReservationMadeMail::class);

    expect(Reservation::count())->toBe(1);
});

test("Can't make reservation with end time before start time", function () {
    $reservation = Reservation::factory()->make();

    login()
        ->post(route("reservation.store"), [
            "isRepeating" => $reservation["is_repeating"],
            "service" => $reservation["service_id"],
            "room" => $reservation["room_id"],
            "description" => $reservation["description"],
            "date" => $reservation["date"],
            "dayOfWeek" => $reservation["day_of_week"],
            "start" => "12:00",
            "end" => "10:00",
        ])
        ->assertSessionHasErrors("end");

    expect(Reservation::count())->toBe(0);
});

test(
    "Can't reserve in the same time and room as another reservation",
    function ($start1, $end1, $start2, $end2, $isRepeating = true) {
        /* @var Reservation $reservation */
        $reservation = Reservation::factory()
            ->state([
                "start" => $start1,
                "end" => $end1,
            ])
            ->approved()
            ->repeating($isRepeating)
            ->create();

        $reservationArray = [
            "isRepeating" => $reservation->is_repeating,
            "service" => $reservation->service_id,
            "room" => $reservation->room_id,
            "description" => $reservation->description,
            "date" => $reservation->date,
            "dayOfWeek" => $reservation->day_of_week,
            "start" => $start2,
            "end" => $end2,
        ];

        adminLogin()
            ->post(route("reservation.store"), $reservationArray)
            ->assertSessionHasErrors("start");

        expect(Reservation::count())->toBe(1);
    },
)->with([
    "Repeating clash #1" => ["10:00", "12:00", "11:00", "13:00"],
    "Repeating clash #2" => ["10:00", "12:00", "10:00", "11:00"],
    "Repeating clash #3" => ["10:00", "12:00", "11:00", "12:00"],
    "Repeating clash #4" => ["10:00", "12:00", "10:00", "12:00"],
    "Repeating clash #5" => ["10:00", "14:00", "11:00", "12:00"],
    "One time clash #1" => ["10:00", "12:00", "11:00", "13:00", false],
    "One time clash #2" => ["10:00", "12:00", "10:00", "11:00", false],
    "One time clash #3" => ["10:00", "12:00", "11:00", "12:00", false],
    "One time clash #4" => ["10:00", "12:00", "10:00", "12:00", false],
    "One time clash #5" => ["10:00", "14:00", "11:00", "12:00", false],
]);

test("Can reserve at consecutive times", function (
    $start1,
    $end1,
    $start2,
    $end2
) {
    $reservation = Reservation::factory()
        ->state([
            "start" => $start1,
            "end" => $end1,
        ])
        ->approved()
        ->create();

    $reservationArray = [
        "isRepeating" => $reservation["is_repeating"],
        "service" => $reservation["service_id"],
        "room" => $reservation["room_id"],
        "description" => $reservation["description"],
        "date" => $reservation["date"],
        "dayOfWeek" => $reservation["day_of_week"],
        "start" => $start2,
        "end" => $end2,
    ];

    adminLogin()
        ->post(route("reservation.store"), $reservationArray)
        ->assertSessionDoesntHaveErrors();

    expect(Reservation::count())->toBe(2);
})->with([
    ["10:00", "12:00", "12:00", "13:00"],
    ["10:00", "12:00", "09:00", "10:00"],
    ["09:00", "10:00", "10:00", "12:00"],
    ["12:00", "13:00", "10:00", "12:00"],
]);

test("Can edit reservation to be consecutive", function (
    $start1,
    $end1,
    $start2,
    $end2
) {
    /* @var Reservation $reservation1 */
    $reservation1 = Reservation::factory()
        ->state([
            "start" => $start1,
            "end" => $end1,
        ])
        ->approved()
        ->create();

    $reservation2 = $reservation1
        ->replicate(["start", "end"])
        ->fill([
            "start" => Carbon::parse($start1)->addHours(2),
            "end" => Carbon::parse($end1)->addHours(2),
        ])
        ->save();

    $reservationArray = [
        "isRepeating" => true,
        "service" => $reservation1["service_id"],
        "room" => $reservation1["room_id"],
        "description" => $reservation1["description"],
        "date" => $reservation1["date"],
        "dayOfWeek" => $reservation1["day_of_week"],
        "start" => $start2,
        "end" => $end2,
    ];

    expect(Reservation::count())->toBe(2);

    adminLogin()
        ->post(route("reservation.update", $reservation2), $reservationArray)
        ->assertSessionDoesntHaveErrors();

    expect(Reservation::count())->toBe(2);
})->with([
    ["10:00", "12:00", "12:00", "13:00"],
    ["10:00", "12:00", "09:00", "10:00"],
    ["09:00", "10:00", "10:00", "12:00"],
    ["12:00", "13:00", "10:00", "12:00"],
]);

test("Reservation wont take effect until approved", function () {
    /* @var Reservation $reservation */
    $reservation = Reservation::factory()
        ->repeating()
        ->notApproved()
        ->create();

    $date = $reservation->nextOccurrence;

    expect(Reservation::date($date)->count())->toBe(0);

    $reservation->approve();

    expect(Reservation::date($date)->count())->toBe(1);
});
