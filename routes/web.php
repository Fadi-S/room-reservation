<?php

use App\Http\Controllers\ApproveReservationController;
use App\Http\Controllers\CreateReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditReservationController;
use App\Http\Controllers\PasswordlessSignInController;
use App\Http\Controllers\ReservationsTableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

if (!app()->environment("production")) {
    Route::get("/login/{id}", function ($user) {
        $user = \App\Models\User::where("id", "=", $user)->firstOrFail();
        Auth::login($user);
        return redirect()->route("home");
    })->name("login.temp");
}

Route::inertia("/login", "Auth/Login")
    ->middleware("guest")
    ->name("login");

Route::post("login-by-email", [
    PasswordlessSignInController::class,
    "sendLink",
]);

Route::get("/table", ReservationsTableController::class)->name("table");

Route::middleware("auth")->group(function () {
    Route::get("/", DashboardController::class)->name("home");

    Route::get("/reserve", [
        CreateReservationController::class,
        "create",
    ])->name("reservation.create");

    Route::post("/reserve", [
        CreateReservationController::class,
        "store",
    ])->name("reservation.store");

    Route::post("/approve/{reservation}", [
        ApproveReservationController::class,
        "approve",
    ])->name("reservation.approve");

    Route::delete("/delete/{reservation}", [
        ApproveReservationController::class,
        "delete",
    ])->name("reservation.delete");

    Route::get("/reservations/not-approved", [
        ApproveReservationController::class,
        "index",
    ])->name("reservation.not-approved");

    Route::get("reservations/{reservation}/edit", [EditReservationController::class, "edit"])->name("reservations.edit");
    Route::patch("reservations/{reservation}", [EditReservationController::class, "update"])->name("reservation.update");

    Route::resource("/users", UserController::class);
});
