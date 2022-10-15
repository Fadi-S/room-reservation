<?php

use Illuminate\Support\Facades\Route;


Route::inertia("/login", "Auth/Login")->name("login");
