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
        $reservations->each(function (Reservation $reservation) {
            if ($reservation->room_id == 17) {
                // El kenissa
                return;
            }

            $roomAvailability = RoomAvailableRule::fromReservation(
                $reservation,
            );

            if (!$roomAvailability->isAvailable()) {
                return;
            }

            $reservation->approve();
        });
    }
}
