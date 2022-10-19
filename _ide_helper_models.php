<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read int|null $reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Reservation
 *
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
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\User $reservedBy
 * @property-read \App\Models\Room $room
 * @property-read \App\Models\Service $service
 * @method static \App\Queries\ReservationQuery|Reservation approved()
 * @method static \App\Queries\ReservationQuery|Reservation forDay($day)
 * @method static \App\Queries\ReservationQuery|Reservation forRoom($room)
 * @method static \App\Queries\ReservationQuery|Reservation newModelQuery()
 * @method static \App\Queries\ReservationQuery|Reservation newQuery()
 * @method static \App\Queries\ReservationQuery|Reservation notStopped($date = null)
 * @method static \App\Queries\ReservationQuery|Reservation overlapping($start, $end)
 * @method static \App\Queries\ReservationQuery|Reservation query()
 * @method static \App\Queries\ReservationQuery|Reservation repeated($isRepeated = true)
 * @method static \App\Queries\ReservationQuery|Reservation valid($date = null)
 * @method static \App\Queries\ReservationQuery|Reservation validBetween($start, $end)
 * @method static \App\Queries\ReservationQuery|Reservation whereApprovedAt($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereApprovedById($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereCreatedAt($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereDate($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereDayOfWeek($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereDescription($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereEnd($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereId($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereIsRepeating($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereRoomId($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereServiceId($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereStart($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereStoppedAt($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereUpdatedAt($value)
 * @method static \App\Queries\ReservationQuery|Reservation whereUserId($value)
 */
	class Reservation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Room
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $location_id
 * @property-read \App\Models\Location $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read int|null $reservations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereName($value)
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Service
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
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
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Service[] $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \App\Queries\UserQuery|User active($isActive = true)
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \App\Queries\UserQuery|User newModelQuery()
 * @method static \App\Queries\UserQuery|User newQuery()
 * @method static \App\Queries\UserQuery|User query()
 * @method static \App\Queries\UserQuery|User search($search)
 * @method static \App\Queries\UserQuery|User whereCreatedAt($value)
 * @method static \App\Queries\UserQuery|User whereEmail($value)
 * @method static \App\Queries\UserQuery|User whereId($value)
 * @method static \App\Queries\UserQuery|User whereIsActive($value)
 * @method static \App\Queries\UserQuery|User whereIsAdmin($value)
 * @method static \App\Queries\UserQuery|User whereName($value)
 * @method static \App\Queries\UserQuery|User wherePassword($value)
 * @method static \App\Queries\UserQuery|User whereRememberToken($value)
 * @method static \App\Queries\UserQuery|User whereTwoFactorRecoveryCodes($value)
 * @method static \App\Queries\UserQuery|User whereTwoFactorSecret($value)
 * @method static \App\Queries\UserQuery|User whereUpdatedAt($value)
 * @method static \App\Queries\UserQuery|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

