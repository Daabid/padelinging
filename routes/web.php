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
 */
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

/**
 * Detalle de un producto específico
 * 
 * @route GET /producto/{id}
 * @controller ProductController@show
 * @name producto.show
 * @param int $id ID del producto a mostrar
 * @return \Illuminate\View\View Vista detallada del producto
 */
Route::get('/producto/{id}', [ProductController::class, 'show'])->name('producto.show');

/**
 * ========================================
 * RUTAS PARA USUARIOS NO AUTENTICADOS
 * ========================================
 * Estas rutas solo están disponibles para usuarios que no han
 * iniciado sesión (middleware 'guest').
 */
Route::middleware('guest')->group(function () {
    
    /**
     * Formulario de inicio de sesión
     * 
     * @route GET /login
     * @controller AuthController@showLogin
     * @name login
     * @return \Illuminate\View\View Formulario de login
     */
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    /**
     * Procesar inicio de sesión
     * 
     * @route POST /login
     * @controller AuthController@login
     * @return \Illuminate\Http\RedirectResponse Redirección después del login
     */
    Route::post('/login', [AuthController::class, 'login']);
    
    /**
     * Procesar registro de nuevo usuario
     * 
     * @route POST /register
     * @controller AuthController@register
     * @return \Illuminate\Http\RedirectResponse Redirección después del registro
     */
    Route::post('/register', [AuthController::class, 'register']);
});

/**
 * ========================================
 * RUTAS PROTEGIDAS
 * ========================================
 * Estas rutas requieren autenticación (middleware 'auth').
 * Solo usuarios logueados pueden acceder a estas funcionalidades.
 */
Route::middleware('auth')->group(function () {
    
    /**
     * ========================================
     * AUTENTICACIÓN
     * ========================================
     */
    
    /**
     * Cerrar sesión del usuario
     * 
     * @route POST /logout
     * @controller AuthController@logout
     * @name logout
     * @return \Illuminate\Http\RedirectResponse Redirección a página principal
     */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /**
     * ========================================
     * CALENDARIO Y RESERVAS
     * ========================================
     */
    
    /**
     * Vista del calendario principal
     * 
     * @route GET /calendario
     * @return \Illuminate\View\View Vista del calendario
     */
    Route::get('/calendario', function () {
        return view('calendario');
    });

    /**
     * Obtener reservas semanales (primera semana)
     * 
     * @route GET /calendario/1
     * @controller ReservaController@reservasSemanal
     * @return \Illuminate\Http\JsonResponse Datos de reservas semanales
     */
    Route::get("/calendario/1", [ReservaController::class, 'reservasSemanal']);
    
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

    /**
     * ========================================
     * CARRITO DE COMPRAS
     * ========================================
     */
    
    /**
     * Agregar producto al carrito
     * 
     * @route POST /carrito/agregar-producto
     * @controller CarritoController@agregarProducto
     * @name carrito.agregar
     * @return \Illuminate\Http\JsonResponse Respuesta de la operación
     */
    Route::post('/carrito/agregar-producto', [CarritoController::class, 'agregarProducto'])->name('carrito.agregar');
    
    /**
     * Ver contenido del carrito
     * 
     * @route GET /carrito
     * @controller CarritoController@verCarrito
     * @name carrito.ver
     * @return \Illuminate\View\View Vista del carrito de compras
     */
    Route::get('/carrito', [CarritoController::class, 'verCarrito'])->name('carrito.ver');
    
    /**
     * Actualizar cantidad de productos en el carrito
     * 
     * @route POST /carrito/actualizar
     * @controller CarritoController@actualizarCantidad
     * @name carrito.actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta de la actualización
     */
    Route::post('/carrito/actualizar', [CarritoController::class, 'actualizarCantidad'])->name('carrito.actualizar');
    
    /**
     * Eliminar producto específico del carrito
     * 
     * @route POST /carrito/eliminar
     * @controller CarritoController@eliminarProducto
     * @name carrito.eliminar
     * @return \Illuminate\Http\JsonResponse Respuesta de la eliminación
     */
    Route::post('/carrito/eliminar', [CarritoController::class, 'eliminarProducto'])->name('carrito.eliminar');
    
    /**
     * Vaciar completamente el carrito
     * 
     * @route POST /carrito/vaciar
     * @controller CarritoController@vaciarCarrito
     * @name carrito.vaciar
     * @return \Illuminate\Http\JsonResponse Respuesta del vaciado
     */
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciarCarrito'])->name('carrito.vaciar');
    
    /**
     * Obtener número de productos en el carrito
     * 
     * @route GET /carrito/count
     * @controller CarritoController@getCount
     * @name carrito.count
     * @return \Illuminate\Http\JsonResponse Cantidad de productos
     */
    Route::get('/carrito/count', [CarritoController::class, 'getCount'])->name('carrito.count');
    
    /**
     * Obtener información completa del carrito
     * 
     * @route GET /carrito/info
     * @controller CarritoController@getCartInfo
     * @name carrito.info
     * @return \Illuminate\Http\JsonResponse Información detallada del carrito
     */
    Route::get('/carrito/info', [CarritoController::class, 'getCartInfo'])->name('carrito.info');
    
    /**
     * Procesar pedido desde el carrito
     * 
     * @route POST /carrito/procesar
     * @controller CarritoController@procesarPedido
     * @name carrito.procesar
     * @return \Illuminate\Http\JsonResponse Respuesta del procesamiento
     */
    Route::post('/carrito/procesar', [CarritoController::class, 'procesarPedido'])->name('carrito.procesar');

    /**
     * ========================================
     * GESTIÓN DE PEDIDOS
     * ========================================
     */
    
    /**
     * Procesar pedido (ruta alternativa)
     * 
     * @route POST /procesar-pedido
     * @controller CarritoController@procesarPedido
     * @name pedido.procesar
     * @return \Illuminate\Http\JsonResponse Respuesta del procesamiento
     */
    Route::post('/procesar-pedido', [CarritoController::class, 'procesarPedido'])->name('pedido.procesar');

    /**
     * Vista de pedidos del usuario
     * 
     * @route GET /mis-pedidos
     * @controller CarritoController@misPedidos
     * @name mis-pedidos
     * @return \Illuminate\View\View Lista de pedidos del usuario
     */
    Route::get('/mis-pedidos', [CarritoController::class, 'misPedidos'])->name('mis-pedidos');

    /**
     * ========================================
     * API ENDPOINTS
     * ========================================
     */
    
    /**
     * API: Obtener pedidos del usuario autenticado
     * 
     * @route GET /api/mis-pedidos
     * @controller CarritoController@obtenerMisPedidos
     * @name api.mis-pedidos
     * @return \Illuminate\Http\JsonResponse Lista de pedidos en formato JSON
     */
    Route::get('/api/mis-pedidos', [CarritoController::class, 'obtenerMisPedidos'])->name('api.mis-pedidos');

    /**
     * API: Ver detalles de un pedido específico
     * 
     * @route GET /api/pedido/{id}
     * @controller CarritoController@verPedido
     * @name api.ver-pedido
     * @param int $id ID del pedido a consultar
     * @return \Illuminate\Http\JsonResponse Detalles del pedido en formato JSON
     */
    Route::get('/api/pedido/{id}', [CarritoController::class, 'verPedido'])->name('api.ver-pedido');

    // Ñema
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

    Route::get('/reserva/pago/realizado', function()
    {
        return view('pagorealizado');
    });

    Route::get("/carruselProductos", [InventarioController::class, 'index']);

    Route::get("/reservaDia/{fecha}", [ReservaController::class, 'reservasSemanal']);

    Route::get("/pistaP/{pista}", [PistaController::class, 'getPrecio']);

    Route::post("/Alquiler/{Usuario}&&{FInicio}&&{FFinal}&&{Precio}", [AlquilerController::class, 'realizarAlquiler']);

});

?>




