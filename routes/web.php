<?php

use App\Http\Controllers\ApproveReservationController;
use App\Http\Controllers\CreateReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationsTableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::inertia("/login", "Auth/Login")
    ->middleware("guest")
    ->name("login");

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

    Route::resource("/users", UserController::class);
});
