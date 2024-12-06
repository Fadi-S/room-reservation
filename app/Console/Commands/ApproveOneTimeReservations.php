<?php

namespace App\Console\Commands;

use App\Enums\FlashMessageType;
use App\Models\Reservation;
use App\Rules\RoomAvailableRule;
use Illuminate\Console\Command;

class ApproveOneTimeReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "reservations:approve";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Approve one time reservations";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $reservations = Reservation::query()
            ->valid(approved: false)
            ->repeated(false)
            ->with(["room", "service", "reservedBy"])
            ->get();
        $count = $reservations->count();

        $this->info("Approved {$count} " . str("reservation")->plural($count));
        $notAllowedRooms = config(
            "app.rooms_that_must_be_approved_manually",
            [],
        );
        $notAllowedLocations = config(
            "app.locations_that_must_be_approved_manually",
            [],
        );
        $reservations->each(function (Reservation $reservation) use (
            $notAllowedRooms,
            $notAllowedLocations
        ) {
            if (
                in_array($reservation->room_id, $notAllowedRooms) ||
                in_array($reservation->room->location_id, $notAllowedLocations)
            ) {
                return;
            }

            $reservation->approve();
        });
    }
}
