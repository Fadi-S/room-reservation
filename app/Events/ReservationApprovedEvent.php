<?php

namespace App\Events;

use App\Http\Resources\ReservationForTableResource;
use App\Models\Reservation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationApprovedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Reservation $reservationModel)
    {
        $this->reservation = ReservationForTableResource::make(
            $this->reservationModel,
        )->resolve();
    }

    public function broadcastAs()
    {
        return "reservations.approved." .
            $this->reservationModel->nextOccurrence->format("Y-m-d");
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("reservations-changed");
    }
}
