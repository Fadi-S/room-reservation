<?php

namespace App\Http\Controllers;

use App\Helpers\PasswordlessLogin;
use App\Models\User;
use Illuminate\Http\Request;

class GenerateLoginLinkController extends Controller
{
    public function __construct(private readonly PasswordlessLogin $passwordlessLogin)
    {
    }

    public function __invoke(Request $request, User $user)
    {
        $this->authorize("admin");

        if ($user->isAdmin()) {
            abort(403);
        }

        return ["link" => $this->passwordlessLogin->forUser($user)];
    }
}
