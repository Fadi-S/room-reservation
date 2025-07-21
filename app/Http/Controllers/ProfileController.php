<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return inertia("Profile/Show", [
            "user" => auth()->user(),
            "url" => route("profile.change-password"),
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        if (!Hash::check($request["current_password"] ?? "", $user->password)) {
            return redirect()
                ->back()
                ->withErrors(["current_password" => __("auth.password")]);
        }

        $validated = $request->validate([
            "current_password" => "required|string|max:255",
            "password" => "required|string|min:8|max:64|confirmed",
        ]);

        $user->password = Hash::make($validated["password"]);
        $user->save();

        $this->flash(__("auth.password_changed"));

        return back();
    }
}
