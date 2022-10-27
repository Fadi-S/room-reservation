<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel("reservation-created", function (User $user) {
    return $user->isAdmin();
});

Broadcast::channel("reservations-changed", fn() => true);
