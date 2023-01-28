<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use App\Mail\SendPasswordlessLoginLink;
use App\Models\User;
use Carbon\CarbonInterval;
use Grosv\LaravelPasswordlessLogin\PasswordlessLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordlessSignInController extends Controller
{
    public function sendLink(Request $request)
    {
        $request->validate([
            "email" => ["required", "string", "max:255"],
        ]);

        $username = trim(strtolower($request->string("email")));

        $user = User::byKey($username);

        if (!$user) {
            return back()->withErrors(["email" => __("auth.failed")]);
        }

        $url = PasswordlessLogin::forUser($user)->generate();

        Mail::to($user)->send(new SendPasswordlessLoginLink($url));

        $this->flash(
            __("ui.email_sent", ["email" => $user->email]),
            FlashMessageType::Info,
            CarbonInterval::seconds(35),
        );

        return redirect()->route("login");
    }
}
