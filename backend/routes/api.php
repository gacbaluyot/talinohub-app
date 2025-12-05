<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
         Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});