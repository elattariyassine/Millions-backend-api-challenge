<?php

use App\Http\Controllers\Api\v1\auth\LoginController;
use App\Http\Controllers\Api\v1\auth\LogoutController;
use App\Http\Controllers\Api\v1\auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function (){
    /* Auth */
    Route::prefix('auth')->group(function () {
        Route::post('register', RegisterController::class);
        Route::post('login', LoginController::class);
        Route::post('logout', LogoutController::class);
    });
});
