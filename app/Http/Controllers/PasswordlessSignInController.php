<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use App\Helpers\PasswordlessLogin;
use App\Mail\SendPasswordlessLoginLink;
use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordlessSignInController extends Controller
{
    public function __construct(private readonly PasswordlessLogin $passwordlessLogin)
    {
    }

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

        $url = $this->passwordlessLogin->forUser($user);

        Mail::to($user)->send(new SendPasswordlessLoginLink($url));

        $this->flash(
            __("ui.email_sent", ["email" => $user->email]),
            FlashMessageType::Info,
            CarbonInterval::seconds(35),
        );

        return redirect()->route("login");
    }

    public function authenticate(Request $request, User $user)
    {
        if (!$this->passwordlessLogin->check($request)) {
            $this->flash(
                __("ui.invalid_link"),
                FlashMessageType::Info,
                CarbonInterval::seconds(35),
            );

            return redirect()
                ->route("login")
                ->withErrors(["token" => __("auth.invalid_token")]);
        }

        auth()->login($user);

        $this->flash(
            __("ui.welcome_back", ["name" => $user->name]),
            FlashMessageType::Success,
            CarbonInterval::seconds(5),
        );

        return redirect()->intended(route("home"));
    }
}
