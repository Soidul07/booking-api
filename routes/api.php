<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\BookingController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::apiResource('organizations', OrganizationController::class);
    Route::post('organizations/{organization}/teams', [TeamController::class, 'store']);
    Route::put('organizations/{organization}/teams/{team}', [TeamController::class, 'update']);
    Route::delete('organizations/{organization}/teams/{team}', [TeamController::class, 'destroy']);
    Route::post('organizations/{organization}/teams/{team}/members', [TeamController::class, 'addMember']);
    Route::delete('organizations/{organization}/teams/{team}/members/{user}', [TeamController::class, 'removeMember']);
    
    Route::apiResource('bookings', BookingController::class);
    Route::post('bookings/{booking}/assign', [BookingController::class, 'assign']);
    Route::post('bookings/{booking}/start', [BookingController::class, 'start']);
    Route::post('bookings/{booking}/complete', [BookingController::class, 'complete']);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});
