<?php

use App\Mail\SendReservationMadeMail;
use App\Models\Reservation;
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
        ->assertSessionHas("message")
        ->assertRedirect(route("home"));

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
    function ($start1, $end1, $start2, $end2) {
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
            ->assertSessionHasErrors("start");

        expect(Reservation::count())->toBe(1);
    },
)->with([
    ["10:00", "12:00", "11:00", "13:00"],
    ["10:00", "12:00", "10:00", "11:00"],
    ["10:00", "12:00", "11:00", "12:00"],
    ["10:00", "12:00", "10:00", "12:00"],
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

test("Reservation wont take effect until approved", function () {
    /* @var Reservation $reservation */
    $reservation = Reservation::factory()
        ->notApproved()
        ->create();

    $date = $reservation->nextOccurrence;

    expect(Reservation::date($date)->count())->toBe(0);

    $reservation->approve();

    expect(Reservation::date($date)->count())->toBe(1);
});
