<?php

namespace App\Enums;

enum FlashMessageType : string
{
    case Success = "success";
    case Warning = "warning";
    case Error = "error";
    case Info = "info";
}
