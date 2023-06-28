<?php

namespace App\Console\Commands;

use App\Models\Reservation;
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
        $reservations->each->approve();
    }
}
