<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\ReservaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/Alquiler', [AlquilerController::class, 'realizarAlquiler']);

Route::post('/Reserva', [ReservaController::class, 'realizarReserva']);