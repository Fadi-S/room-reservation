<?php

namespace App\Http\Controllers;

use App\Enums\FlashMessageType;
use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->only("email", "password");

        $user = User::byKey($credentials["email"]);

        if ($user && Hash::check($credentials["password"], $user->password)) {
            auth()->login($user);

            $this->flash(
                __("auth.success"),
                FlashMessageType::Success,
                CarbonInterval::seconds(5),
            );

            return redirect(route("home"));
        }

        //        $this->flash(
        //            __("auth.failed"),
        //            FlashMessageType::Error,
        //            CarbonInterval::seconds(5),
        //        );

        return back()
            ->with("email", $request->input("email"))
            ->withErrors([
                "email" => __("auth.failed"),
            ]);
    }
}
