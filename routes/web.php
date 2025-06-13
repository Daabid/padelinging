<?php

/**
 * Archivo de definición de rutas de la aplicación
 * 
 * Este archivo contiene todas las rutas HTTP de la aplicación, organizadas
 * en secciones públicas y protegidas según los requerimientos de autenticación.
 * 
 * @package App\Routes
 * @author David
 * @version 1.0
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PistaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CarritoController;

/**
 * ========================================
 * RUTAS PÚBLICAS
 * ========================================
 * Estas rutas no requieren autenticación y están disponibles
 * para todos los visitantes del sitio web.
 */

/**
 * Ruta principal de la aplicación
 * 
 * @route GET /
 * @return \Illuminate\View\View Vista de bienvenida
 */use App\Http\Controllers\AlquilerController;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Catálogo de productos públicos
 * 
 * @route GET /catalogo
 * @controller ProductController@index
 * @name catalogo
 * @return \Illuminate\View\View Lista de productos disponibles
 */
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');
Route::get('/producto/{id}', [ProductController::class, 'show'])->name('producto.show'); //Página más información del producto

// Paginaa de reserva de pista
Route::get('/calendario', function()
{
    return view('calendario');
});

    /**
     * Obtener reservas semanales (primera semana)
     * 
     * @route GET /calendario/1
     * @controller ReservaController@reservasSemanal
     * @return \Illuminate\Http\JsonResponse Datos de reservas semanales
     */
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

    /**
     * Obtener reservas para un día específico
     * 
     * @route GET /reservaDia/{fecha}
     * @controller ReservaController@reservasSemanal
     * @param string $fecha Fecha en formato YYYY-MM-DD
     * @return \Illuminate\Http\JsonResponse Reservas del día especificado
     */
    Route::get("/reservaDia/{fecha}", [ReservaController::class, 'reservasSemanal']);

    /**
     * ========================================
     * INVENTARIO
     * ========================================
     */
    
    /**
     * Carrusel de productos del inventario
     * 
     * @route GET /carruselProductos
     * @controller InventarioController@index
     * @return \Illuminate\Http\JsonResponse Productos para carrusel
     */
    Route::get("/carruselProductos", [InventarioController::class, 'index']);

Route::get("/reservaDia/{fecha}", [ReservaController::class, 'reservasSemanal']);
?>