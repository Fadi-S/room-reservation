<?php

use App\Http\Controllers\ApproveReservationController;
use App\Http\Controllers\CreateReservationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::inertia("/login", "Auth/Login")->middleware("guest")->name("login");

Route::middleware("auth")->group(function () {
    Route::get("/", DashboardController::class);

    Route::get("/reserve", [CreateReservationController::class, "create"]);
    Route::post("/reserve", [CreateReservationController::class, "store"]);

    Route::post("/approve/{reservation}", ApproveReservationController::class);
});
