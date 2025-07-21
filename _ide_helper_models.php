<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $reservation_id
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Reservation $reservation
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absence whereUserId($value)
 */
	class Absence extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $reservation_id
 * @property int $paused_by_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $pausedBy
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause wherePausedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pause whereUpdatedAt($value)
 */
	class Pause extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $service_id
 * @property int $room_id
 * @property int $user_id
 * @property string|null $description
 * @property int|null $approved_by_id
 * @property string $start
 * @property string $end
 * @property \Illuminate\Support\Carbon|null $date
 * @property int $day_of_week
 * @property bool $is_repeating
 * @property \Illuminate\Support\Carbon|null $stopped_at
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absence> $absences
 * @property-read int|null $absences_count
 * @property-read \App\Models\User|null $approvedBy
 * @property-read mixed $day_of_week_name
 * @property-read mixed $next_occurrence
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pause> $pauses
 * @property-read int|null $pauses_count
 * @property-read \App\Models\User $reservedBy
 * @property-read \App\Models\Room $room
 * @property-read \App\Models\Service $service
 * @method static \App\Queries\ReservationQuery<static>|Reservation approved(bool $approved = true)
 * @method static \App\Queries\ReservationQuery<static>|Reservation date(\Carbon\Carbon $date)
 * @method static \Database\Factories\ReservationFactory factory($count = null, $state = [])
 * @method static \App\Queries\ReservationQuery<static>|Reservation forDay($day)
 * @method static \App\Queries\ReservationQuery<static>|Reservation forRoom($room)
 * @method static \App\Queries\ReservationQuery<static>|Reservation newModelQuery()
 * @method static \App\Queries\ReservationQuery<static>|Reservation newQuery()
 * @method static \App\Queries\ReservationQuery<static>|Reservation notPaused($date = null)
 * @method static \App\Queries\ReservationQuery<static>|Reservation notStopped($date = null)
 * @method static \App\Queries\ReservationQuery<static>|Reservation overlapping($start, $end)
 * @method static \App\Queries\ReservationQuery<static>|Reservation query()
 * @method static \App\Queries\ReservationQuery<static>|Reservation repeated($isRepeated = true)
 * @method static \App\Queries\ReservationQuery<static>|Reservation valid($date = null, $approved = true)
 * @method static \App\Queries\ReservationQuery<static>|Reservation validBetween($start, $end)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereApprovedAt($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereApprovedById($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereCreatedAt($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereDate($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereDayOfWeek($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereDescription($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereEnd($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereId($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereIsRepeating($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereRoomId($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereServiceId($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereStart($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereStoppedAt($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereUpdatedAt($value)
 * @method static \App\Queries\ReservationQuery<static>|Reservation whereUserId($value)
 */
	class Reservation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $location_id
 * @property-read mixed $full_name
 * @property-read \App\Models\Location $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereName($value)
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string $email
 * @property string $username
 * @property int $is_admin
 * @property int $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pause> $pauses
 * @property-read int|null $pauses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \App\Queries\UserQuery<static>|User active($isActive = true)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \App\Queries\UserQuery<static>|User newModelQuery()
 * @method static \App\Queries\UserQuery<static>|User newQuery()
 * @method static \App\Queries\UserQuery<static>|User query()
 * @method static \App\Queries\UserQuery<static>|User search($search)
 * @method static \App\Queries\UserQuery<static>|User whereCreatedAt($value)
 * @method static \App\Queries\UserQuery<static>|User whereEmail($value)
 * @method static \App\Queries\UserQuery<static>|User whereId($value)
 * @method static \App\Queries\UserQuery<static>|User whereIsActive($value)
 * @method static \App\Queries\UserQuery<static>|User whereIsAdmin($value)
 * @method static \App\Queries\UserQuery<static>|User whereName($value)
 * @method static \App\Queries\UserQuery<static>|User wherePassword($value)
 * @method static \App\Queries\UserQuery<static>|User whereRememberToken($value)
 * @method static \App\Queries\UserQuery<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \App\Queries\UserQuery<static>|User whereTwoFactorSecret($value)
 * @method static \App\Queries\UserQuery<static>|User whereUpdatedAt($value)
 * @method static \App\Queries\UserQuery<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

