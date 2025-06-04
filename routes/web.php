<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\InventarioController;

Route::get('/', function () {
    return view('welcome');
});

// Paginaa de reserva de pista
Route::get('/calendario', function()
{
    return view('calendario');
});

Route::get("/calendario/1", [ReservaController::class, 'reservasSemanal']);

Route::get("/carruselProductos", [InventarioController::class, 'index']);

Route::get("/reservaDia/{fecha}", [ReservaController::class, 'reservasSemanal']);
?>