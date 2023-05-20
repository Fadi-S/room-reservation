<?php

namespace App\Http\Controllers;

use App\Models\User;
use Grosv\LaravelPasswordlessLogin\PasswordlessLogin;
use Illuminate\Http\Request;

class GenerateLoginLinkController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $this->authorize("admin");

        if ($user->isAdmin()) {
            abort(403);
        }

        return ["link" => PasswordlessLogin::forUser($user)->generate()];
    }
}
