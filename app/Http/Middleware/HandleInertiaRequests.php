<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = "app";

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $user = auth()->user();
        $data = [];

        if ($user) {
            $userArray = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "username" => $user->username,
                "imageUrl" => asset("/images/defaultPicture.png"),
                "isAdmin" => $user->isAdmin(),
            ];
            $reservations = $user->isAdmin()
                ? Reservation::query()
                : $user->reservations();

            $data["pendingReservationsCount"] = $reservations
                ->valid(approved: false)
                ->count();
        }

        return array_merge(parent::share($request), [
            "auth" => [
                "user" => $userArray ?? null,
            ],
            "data" => $data,
            "flash" => [
                "message" => fn() => session("message") ?? session("success"),
                "type" => fn() => session("type", "success"),
                "important" => fn() => session("important", false),
            ],
        ]);
    }
}
