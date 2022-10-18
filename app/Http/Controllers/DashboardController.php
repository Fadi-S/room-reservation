<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $reservations = Reservation::query()
            ->valid()
            ->get()
            ->groupBy("day_of_week");

        return inertia("Home");
    }
}
