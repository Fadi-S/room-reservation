<?php

namespace App\Http\Controllers;

use App\Actions\RenderTable;
use App\Models\Location;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;

class PrintReservationsController extends Controller
{
    private function download($html, $width, $height, $scale)
    {
        return response()->streamDownload(
            function () use ($html, $width, $height, $scale) {
                echo Browsershot::html($html)
                    ->landscape()
                    ->showBackground()
                    ->windowSize($width, $height)
                    ->scale($scale)
                    ->fullPage()
                    ->pdf();
            },
            headers: [
                "Content-Type" => "application/pdf",
                "Content-Disposition" => "attachment; filename=table.pdf",
            ],
        );
    }

    public function index(Request $request)
    {
        $date = $request->date("date");
        $date ??= now();
        $date->setHour(9);

        $html = RenderTable::for($date)
            ->view()
            ->toHtml();

        return $this->download($html, 2200, 1080, 0.45);
    }

    public function show(Request $request, Location $location)
    {
        $html = RenderTable::for($request->date("date"), $location->id)
            ->view()
            ->toHtml();

        return $this->download($html, 1920, 1080, 0.5);
    }
}
