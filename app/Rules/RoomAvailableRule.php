<?php

namespace App\Rules;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RoomAvailableRule implements Rule
{
    protected ?Reservation $reservation;
    protected string $start;
    protected string $end;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $roomId,
        protected ?Carbon $date,
        protected $dayOfWeek,
        $start,
        $end,
        protected $ignore = null
    ) {
        $this->dayOfWeek ??= $this->date?->dayOfWeek;

        if ($start instanceof Carbon) {
            $this->start = $start->format("H:i:s");
            $this->end = $end->format("H:i:s");
        }

        if (is_string($start)) {
            $this->start = $start;
            $this->end = $end;
        }
    }

    public static function fromReservation(Reservation $reservation): self
    {
        return new self(
            $reservation->room_id,
            $reservation->date,
            $reservation->day_of_week,
            $reservation->start,
            $reservation->end,
            $reservation->id,
        );
    }

    public function isAvailable(): bool
    {
        return $this->passes(null, null);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->reservation = Reservation::query()
            ->when(
                $this->ignore,
                fn($query) => $query->where("id", "<>", $this->ignore),
            )
            ->valid($this->date)
            ->forRoom($this->roomId)
            ->forDay($this->dayOfWeek)
            ->overlapping($this->start, $this->end)
            ->with("service:id,name")
            ->first();

        return !$this->reservation;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $reservation = "{$this->reservation->description} {$this->reservation->service->name}";

        return __("validation.conflict", ["reservation" => $reservation]);
    }
}
