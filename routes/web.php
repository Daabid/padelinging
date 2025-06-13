<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\PistaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AlquilerController;

Route::get('/', function () {
    return view('welcome');
});

//Rutas para el login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::post('/register', [AuthController::class, 'register']);

//Rutas para la tienda
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');
Route::get('/producto/{id}', [ProductController::class, 'show'])->name('producto.show'); //Página más información del producto

// Pagina de reserva de pista
Route::get('/reserva', function()
{
    return view('calendario');
});

// Pagina para realizar pago
Route::get('/reserva/pago', function()
{
    return view('pago');
});

Route::post('/reserva/pago/{Usuario}&&{Pista}&&{Alquiler}&&{FInicio}&&{FFinal}', [ReservaController::class, 'realizarReserva']);

Route::get('/reserva/5', function()
{
    return view('pagorealizado');
});

Route::get("/carruselProductos", [InventarioController::class, 'index']);

Route::get("/reservaDia/{fecha}", [ReservaController::class, 'reservasSemanal']);

Route::get("/pistaP/{pista}", [PistaController::class, 'getPrecio']);

Route::post("/Alquiler/{Usuario}&&{FInicio}&&{FFinal}&&{Precio}", [AlquilerController::class, 'realizarAlquiler'])
?>