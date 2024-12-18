<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): Response
    {
        if ($user->hasAccessTo($reservation) && !$reservation->isApproved()) {
            return Response::allow();
        }

        return $user->isAdmin()
            ? Response::allow()
            : Response::deny("You are not allowed to edit reservations.");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): Response
    {
        if ($user->hasAccessTo($reservation)) {
            return Response::allow();
        }

        return $user->isAdmin()
            ? Response::allow()
            : Response::deny("You are not allowed to delete reservations.");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny("You are not allowed to edit reservations.");
    }

    public function pause(User $user, Reservation $reservation): Response
    {
        if ($user->hasAccessTo($reservation)) {
            return Response::allow();
        }

        return Response::deny("You are not allowed to pause this reservation.");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): Response
    {
        if ($user->hasAccessTo($reservation) && !$reservation->isApproved()) {
            return Response::allow();
        }

        return $user->isAdmin()
            ? Response::allow()
            : Response::deny("You are not allowed to edit reservations.");
    }
}
