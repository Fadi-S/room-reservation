<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Queries\UserQuery;
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

    public function allowedServices(): Builder
    {
        if ($this->isAdmin()) {
            return Service::query()->toBase();
        }

        return $this->services()->toBase();
    }
}
