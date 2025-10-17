<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarReservationController;

//Route::middleware('auth:sanctum')->get('/available-cars', [CarReservationController::class, 'availableCars']);
Route::get('/available-cars', [CarReservationController::class, 'availableCars']);
