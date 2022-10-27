<?php

namespace App\Http\Controllers;

use App\Mail\SendPasswordlessLoginLink;
use App\Models\User;
use Grosv\LaravelPasswordlessLogin\PasswordlessLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordlessSignInController extends Controller
{
    public function sendLink(Request $request)
    {
        if (\Auth::check()) {
            return redirect()->route("home");
        }

        $username = trim(strtolower($request->email));

        $user = User::query()
            ->where("email", "=", $username)
            ->orWhere("username", "=", $username)
            ->first();

        if (!$user) {
            return back()->withErrors([
                "email" => __("auth.failed"),
            ]);
        }

        $url = PasswordlessLogin::forUser($user)->generate();

        Mail::to($user)->send(new SendPasswordlessLoginLink($url));

        session()->flash(
            "message",
            "لقد تم ارسال طلب لتسجيل الدخول علي " . $user->email,
        );
        session()->flash("type", "info");
        session()->flash("important", 35000);

        return redirect()->route("login");
    }
}
