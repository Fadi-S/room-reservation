<?php

namespace App\Rules;

use App\Models\Reservation;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RoomAvailableRule implements ValidationRule
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

        $this->start = Carbon::parse($start)->format("H:i:s");
        $this->end = Carbon::parse($end)->format("H:i:s");
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
        if ($this->date) {
            $start = $this->date->copy()->setTimeFrom($this->start);

            $end = $this->date->copy()->setTimeFrom($this->end);
        }

        $start ??= now()
            ->next((int) $this->dayOfWeek)
            ->setTimeFrom($this->start);

        $end ??= now()
            ->next((int) $this->dayOfWeek)
            ->setTimeFrom($this->end)
            ->addMonths(3);

        $this->reservation = Reservation::query()
            ->when(
                $this->ignore,
                fn($query) => $query->where("id", "<>", $this->ignore),
            )
            ->validBetween($start, $end)
            ->overlapping($this->start, $this->end)
            ->forRoom($this->roomId)
            ->forDay($this->dayOfWeek)
            ->with("service:id,name")
            ->first();

        return !$this->reservation;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    protected function message(): string
    {
        $reservation = "{$this->reservation->description} {$this->reservation->service->name}";

        return __("validation.conflict", ["reservation" => $reservation]);
    }

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (!$this->isAvailable()) {
            $fail($this->message());
        }
    }
}
