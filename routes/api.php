<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarReservationController;

Route::middleware('auth:sanctum')->get('/cars', [CarReservationController::class, 'getCars']);
