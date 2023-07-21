<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Queries\ReservationQuery;
use App\Queries\UserQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = ["password", "remember_token"];

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public static function query(): UserQuery
    {
        return parent::query();
    }

    #[Pure]
    public function newEloquentBuilder($query): UserQuery {
        return new UserQuery($query);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function getRouteKeyName(): string
    {
        return "username";
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function pauses(): HasMany
    {
        return $this->hasMany(Pause::class, "paused_by_id");
    }

    public function allowedServices(): Builder
    {
        if ($this->isAdmin()) {
            return Service::query()->toBase();
        }

        return $this->services()->toBase();
    }

    public function hasAccessTo(Reservation $reservation): bool
    {
        $this->load("services");

        return $reservation->user_id == $this->id ||
            $this->isAdmin() ||
            $this->services->where("id", $reservation->service_id)->count() > 0;
    }

    public static function byKey($key): ?self
    {
        return self::where("email", "=", $key)
            ->orWhere("username", "=", $key)
            ->first();
    }

    public function email(): Attribute
    {
        return new Attribute(
            set: fn($value) => ($this->attributes["email"] = trim(
                strtolower($value),
            )),
        );
    }

    public function reservations(): HasMany|ReservationQuery
    {
        return $this->hasMany(Reservation::class);
    }

    public function username(): Attribute
    {
        return new Attribute(
            set: fn($value) => ($this->attributes["username"] = trim(
                strtolower($value),
            )),
        );
    }
}
