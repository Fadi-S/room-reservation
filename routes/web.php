<?php

use App\Http\Controllers\ApproveReservationController;
use App\Http\Controllers\CreateReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditReservationController;
use App\Http\Controllers\GenerateLoginLinkController;
use App\Http\Controllers\PasswordlessSignInController;
use App\Http\Controllers\PersonalReservationController;
use App\Http\Controllers\ReservationsTableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

if (app()->environment("local")) {
    Route::get("/login/{id}", function ($user) {
        $user = \App\Models\User::where("id", "=", $user)->firstOrFail();
        Auth::login($user);
        return redirect()->route("home");
    })->name("login.temp");
}

Route::inertia("/login", "Auth/Login")
    ->middleware("guest")
    ->name("login");

Route::middleware("guest")->post("login-by-email", [
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

    Route::delete("/reservations/{reservation}", [
        EditReservationController::class,
        "destroy",
    ])->name("reservation.stop");

    Route::get("/reservations/not-approved", [
        ApproveReservationController::class,
        "index",
    ])->name("reservation.not-approved");

    Route::get("reservations/{reservation}/edit", [
        EditReservationController::class,
        "edit",
    ])->name("reservations.edit");

    Route::get("personal/reservations", [
        PersonalReservationController::class,
        "index",
    ])->name("reservations.personal");

    Route::patch("reservations/{reservation}", [
        EditReservationController::class,
        "update",
    ])->name("reservation.update");

    Route::resource("/users", UserController::class);

    Route::get("users/{user}/link", GenerateLoginLinkController::class)->name(
        "users.link",
    );
});
