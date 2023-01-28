<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use Carbon\CarbonInterval;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function flash(
        string $message,
        FlashMessageType $type = FlashMessageType::Success,
        CarbonInterval $for = null
    ): self {
        session()->flash("message", $message);
        session()->flash("type", $type->value);

        if ($for !== null) {
            session()->flash("important", $for->milliseconds);
        }

        return $this;
    }

    protected function flashPermanently(
        string $message,
        FlashMessageType $type = null
    ): self {
        $this->flash($message, $type);

        session()->flash("important");

        return $this;
    }
}
