<?php

namespace App\Rules;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RoomAvailableRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $roomId,
        protected ?Carbon $date,
        protected $dayOfWeek,
        protected $start,
        protected $end
    ) {
        $this->dayOfWeek ??= $this->date?->dayOfWeek;
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
        return Reservation::query()
            ->valid($this->date)
            ->forRoom($this->roomId)
            ->where("day_of_week", "=", $this->dayOfWeek)
            ->overlapping($this->start, $this->end)
            ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Time overlap";
    }
}
