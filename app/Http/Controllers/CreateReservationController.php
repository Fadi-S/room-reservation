<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateReservationController extends Controller
{
    public function create()
    {
        return inertia("ReservationForm");
    }

    public function store(Request $request)
    {
        $request->validate([]);
    }
}
