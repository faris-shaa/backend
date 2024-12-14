<?php

use App\Http\Controllers\Organizer\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["guest"]],function (){
    Route::get("login", [LoginController::class, "showLoginForm"])->name("login");
    Route::post("login", [LoginController::class, "login"]);
});
