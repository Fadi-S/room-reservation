<?php

namespace App\Http\Controllers;

use App\Actions\RenderTable;
use App\Http\Resources\ReservationForTableResource;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReservationsTVController extends Controller
{
    public function __invoke(Request $request)
    {
        return RenderTable::for()->view();
    }
}
