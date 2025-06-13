<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Carrito;
use App\Models\Venta;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

/**
 * Controlador para la gestión del carrito de compras
 * 
 * @package App\Http\Controllers
 * @author David
 * @version 1.0
 */
class CarritoController extends Controller
{
    /**
     * Limpiar productos del carrito que ya no tienen stock suficiente
     *
     * @return array Lista de productos eliminados por falta de stock
     */
    private function limpiarCarritoSinStock()
    {
        $carrito = Session::get('carrito', []);
        $carritoLimpio = [];
        $productosEliminados = [];

        foreach ($carrito as $productoId => $item) {
            $producto = Inventario::find($productoId);
            
            if ($producto && $producto->Stock >= $item['cantidad']) {
                $carritoLimpio[$productoId] = $item;
                $carritoLimpio[$productoId]['stock_disponible'] = $producto->Stock;
            } else {
                $productosEliminados[] = $item['nombre'];
            }
        }

        if (count($productosEliminados) > 0) {
            Session::put('carrito', $carritoLimpio);
            Log::info('Productos eliminados del carrito por falta de stock:', $productosEliminados);
        }

        return $productosEliminados;
    }

    /**
     * Generar un ID único para el pedido (máximo 20 caracteres)
     *
     * @return string ID único del pedido en formato PED + fecha + random
     */
    private function generarIdPedido()
    {
        do {
            $id = 'PED' . date('ymdHis') . rand(100, 999);
        } while (Carrito::where('ID', $id)->exists());
        
        return $id;
    }

    /**
     * Determinar el estado del pedido basado en la fecha
     *
     * @param string $fechaPedido Fecha del pedido
     * @return array Información del estado con clase CSS, icono y mensaje
     */
    private function determinarEstadoPedido($fechaPedido)
    {
        $fechaPedido = Carbon::parse($fechaPedido);
        $ahora = Carbon::now();
        $diasTranscurridos = $fechaPedido->diffInDays($ahora);
        
        if ($diasTranscurridos >= 2) {
            return [
                'estado' => 'entregado',
                'clase_css' => 'success',
                'icono' => 'fa-check-circle',
                'mensaje' => 'Entregado'
            ];
        } elseif ($diasTranscurridos >= 1) {
            return [
                'estado' => 'en_camino',
                'clase_css' => 'warning',
                'icono' => 'fa-truck',
                'mensaje' => 'En camino'
            ];
        } else {
            return [
                'estado' => 'preparando',
                'clase_css' => 'info',
                'icono' => 'fa-clock',
                'mensaje' => 'Preparando'
            ];
        }
    }

    /**
     * Agregar producto al carrito
     *
     * @param Request $request Datos del producto (producto_id, cantidad)
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado
     */
    public function agregarProducto(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para añadir productos al carrito',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'producto_id' => 'required|integer|exists:Inventario,IDProducto',
            'cantidad' => 'integer|min:1'
        ]);

        $productoId = $request->producto_id;
        $cantidad = $request->cantidad ?? 1;

        $producto = Inventario::find($productoId);
        
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        // Limpiar carrito de productos sin stock
        $carrito = Session::get('carrito', []);
        $carritoLimpio = [];
        $productosEliminados = [];

        foreach ($carrito as $id => $item) {
            $prod = Inventario::find($id);
            if ($prod && $prod->Stock >= $item['cantidad']) {
                $carritoLimpio[$id] = $item;
                $carritoLimpio[$id]['stock_disponible'] = $prod->Stock;
            } else {
                $productosEliminados[] = $item['nombre'];
            }
        }

        Session::put('carrito', $carritoLimpio);
        $carrito = $carritoLimpio;

        // Verificar stock disponible
        $cantidadEnCarrito = isset($carrito[$productoId]) ? $carrito[$productoId]['cantidad'] : 0;
        $nuevaCantidad = $cantidadEnCarrito + $cantidad;

        if ($nuevaCantidad > $producto->Stock) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente. Disponible: {$producto->Stock}, en carrito: {$cantidadEnCarrito}",
                'productos_eliminados' => $productosEliminados,
                'stock_real' => $producto->Stock
            ], 400);
        }

        // Agregar o actualizar producto en carrito
        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] = $nuevaCantidad;
            $carrito[$productoId]['stock_disponible'] = $producto->Stock;
        } else {
            $carrito[$productoId] = [
                'id' => $producto->IDProducto,
                'nombre' => $producto->Nombre,
                'precio' => $producto->Precio,
                'cantidad' => $cantidad,
                'imagen' => $producto->URL,
                'stock_disponible' => $producto->Stock
            ];
        }

        Session::put('carrito', $carrito);

        // Calcular totales
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalPrecio = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $carrito));

        $response = [
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'count' => $totalItems,
            'carrito_count' => $totalItems,
            'total_precio' => number_format($totalPrecio, 2),
            'producto' => $carrito[$productoId]
        ];

        if (!empty($productosEliminados)) {
            $response['productos_eliminados'] = $productosEliminados;
            $response['mensaje_adicional'] = 'Algunos productos fueron eliminados del carrito por falta de stock.';
        }

        return response()->json($response);
    }

    /**
     * Mostrar la vista del carrito
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function verCarrito()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Debes iniciar sesión para ver tu carrito');
        }

        $carrito = Session::get('carrito', []);
        $totalPrecio = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $carrito));

        return view('carrito', compact('carrito', 'totalPrecio'));
    }

    /**
     * Actualizar cantidad de un producto en el carrito
     *
     * @param Request $request Datos del producto (producto_id, cantidad)
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarCantidad(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para modificar el carrito',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:0'
        ]);

        $productoId = $request->producto_id;
        $cantidad = $request->cantidad;
        $carrito = Session::get('carrito', []);

        if (!isset($carrito[$productoId])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en carrito'
            ], 404);
        }

        if ($cantidad == 0) {
            unset($carrito[$productoId]);
        } else {
            $producto = Inventario::find($productoId);
            if ($cantidad > $producto->Stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente. Disponible: {$producto->Stock}"
                ], 400);
            }
            
            $carrito[$productoId]['cantidad'] = $cantidad;
        }

        Session::put('carrito', $carrito);

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalPrecio = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $carrito));

        return response()->json([
            'success' => true,
            'count' => $totalItems,
            'carrito_count' => $totalItems,
            'total_precio' => number_format($totalPrecio, 2)
        ]);
    }

    /**
     * Eliminar producto del carrito
     *
     * @param Request $request Datos del producto (producto_id)
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarProducto(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para modificar el carrito',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        $productoId = $request->producto_id;
        $carrito = Session::get('carrito', []);

        if (isset($carrito[$productoId])) {
            unset($carrito[$productoId]);
            Session::put('carrito', $carrito);
        }

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'count' => $totalItems,
            'carrito_count' => $totalItems
        ]);
    }

    /**
     * Vaciar carrito completo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function vaciarCarrito()
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para vaciar el carrito',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        Session::forget('carrito');
        
        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'count' => 0,
            'carrito_count' => 0
        ]);
    }

    /**
     * Obtener información básica del carrito
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartInfo()
    {
        $carrito = Session::get('carrito', []);
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalPrecio = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $carrito));

        $response = [
            'count' => $totalItems,
            'total_precio' => number_format($totalPrecio, 2),
            'items' => count($carrito),
            'authenticated' => auth()->check()
        ];

        if (auth()->check()) {
            $response['carrito'] = $carrito;
        }

        return response()->json($response);
    }

    /**
     * Obtener solo el conteo del carrito
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        $carrito = Session::get('carrito', []);
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        
        return response()->json([
            'count' => $totalItems,
            'carrito_count' => $totalItems,
            'authenticated' => auth()->check()
        ]);
    }

    /**
     * Procesar el pedido y guardarlo en la base de datos
     *
     * @param Request $request Datos del formulario de checkout
     * @return \Illuminate\Http\JsonResponse
     */
    public function procesarPedido(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para procesar el pedido',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $validatedData = $request->validate([
                'dni' => 'required|string',
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telefono' => 'required|string',
                'direccion' => 'required|string|max:500',
                'ciudad' => 'required|string|max:255',
                'codigo_postal' => 'required|string',
                'metodo_pago' => 'required|string|in:tarjeta,paypal,transferencia',
                'terminos' => 'required',
                // Campos opcionales de tarjeta
                'numero_tarjeta' => 'nullable|string',
                'fecha_expiracion' => 'nullable|string',
                'cvv' => 'nullable|string',
                'titular_tarjeta' => 'nullable|string',
            ]);

            $carritoSesion = Session::get('carrito', []);
            
            if (empty($carritoSesion)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El carrito está vacío'
                ], 400);
            }

            DB::beginTransaction();
            
            // Calcular totales
            $subtotal = array_sum(array_map(function($item) {
                return $item['precio'] * $item['cantidad'];
            }, $carritoSesion));
            
            $envio = $subtotal >= 50 ? 0 : 5.99;
            $total = $subtotal + $envio;

            // Verificar stock antes de procesar
            foreach ($carritoSesion as $item) {
                $producto = Inventario::find($item['id']);
                if (!$producto || $producto->Stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$item['nombre']}");
                }
            }

            // Crear registro en tabla carrito
            $carrito = new Carrito();
            $carrito->ID = $this->generarIdPedido();
            $carrito->Usuario = $validatedData['dni'];
            $carrito->Precio = $total;
            $carrito->Fecha = now();
            
            // Asignar campos adicionales según la estructura de la tabla
            $this->asignarCamposCarrito($carrito, $validatedData);
            $carrito->save();

            // Crear registros en tabla venta
            foreach ($carritoSesion as $item) {
                $venta = new Venta();
                $venta->Producto = $item['id'];
                $venta->Carrito = $carrito->ID;
                $venta->Precio = $item['precio'];
                
                // Asignar cantidad según la estructura de la tabla
                if (Schema::hasColumn('Venta', 'Cantidad')) {
                    $venta->Cantidad = $item['cantidad'];
                } elseif (Schema::hasColumn('Venta', 'cantidad')) {
                    $venta->cantidad = $item['cantidad'];
                }
                
                $venta->save();

                // Actualizar stock
                DB::table('Inventario')
                    ->where('IDProducto', $item['id'])
                    ->decrement('Stock', $item['cantidad']);
            }

            Session::forget('carrito');
            DB::commit();

            Log::info('Pedido procesado correctamente', [
                'numero_pedido' => $carrito->ID,
                'dni' => $validatedData['dni'],
                'total' => $total,
                'productos' => count($carritoSesion)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pedido procesado correctamente',
                'numero_pedido' => $carrito->ID,
                'total' => $total,
                'redirect_url' => '/catalogo'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error procesando pedido: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Asignar campos adicionales al modelo Carrito según la estructura de la tabla
     *
     * @param Carrito $carrito Instancia del modelo Carrito
     * @param array $validatedData Datos validados del formulario
     * @return void
     */
    private function asignarCamposCarrito(Carrito $carrito, array $validatedData)
    {
        if (Schema::hasColumn('Carrito', 'email')) {
            $carrito->email = $validatedData['email'];
        }
        
        if (Schema::hasColumn('Carrito', 'dirección')) {
            $carrito->dirección = $validatedData['direccion'];
        } elseif (Schema::hasColumn('Carrito', 'direccion')) {
            $carrito->direccion = $validatedData['direccion'];
        }
        
        if (Schema::hasColumn('Carrito', 'ciudad')) {
            $carrito->ciudad = $validatedData['ciudad'];
        }
        
        if (Schema::hasColumn('Carrito', 'codigo_postal')) {
            $carrito->codigo_postal = $validatedData['codigo_postal'];
        }
        
        if (Schema::hasColumn('Carrito', 'metodo_pago')) {
            $carrito->metodo_pago = $validatedData['metodo_pago'];
        }
    }

    /**
     * Mostrar la página de mis pedidos
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function misPedidos()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Debes iniciar sesión para ver tus pedidos');
        }

        return view('mis-pedidos');
    }

    /**
     * API para obtener los pedidos del usuario autenticado
     *
     * @param Request $request Debe contener el DNI del usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerMisPedidos(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para ver tus pedidos',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        $dni = $request->get('dni');
        
        if (!$dni) {
            return response()->json([
                'success' => false,
                'message' => 'DNI requerido'
            ], 400);
        }

        try {
            $pedidos = Carrito::with(['ventas.producto'])
                ->where('Usuario', $dni)
                ->orderBy('Fecha', 'desc')
                ->get();

            $pedidosConEstado = $pedidos->map(function($pedido) {
                $estadoInfo = $this->determinarEstadoPedido($pedido->Fecha);
                
                $cantidadTotal = $pedido->ventas->sum(function($venta) {
                    return $venta->Cantidad ?? $venta->cantidad ?? 0;
                });

                return [
                    'id' => $pedido->ID,
                    'fecha' => $pedido->Fecha,
                    'fecha_formateada' => Carbon::parse($pedido->Fecha)->format('d/m/Y H:i'),
                    'precio' => $pedido->Precio,
                    'estado' => $estadoInfo,
                    'cantidad_productos' => $cantidadTotal,
                    'productos' => $pedido->ventas->map(function($venta) {
                        return $this->formatearProductoVenta($venta);
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'pedidos' => $pedidosConEstado,
                'total_pedidos' => $pedidosConEstado->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo pedidos: ' . $e->getMessage(), [
                'dni' => $dni,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los pedidos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver detalles de un pedido específico
     *
     * @param string $idPedido ID del pedido
     * @return \Illuminate\Http\JsonResponse
     */
    public function verPedido($idPedido)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para ver el pedido',
                'type' => 'auth_required',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $pedido = Carrito::with(['ventas.producto'])
                ->where('ID', $idPedido)
                ->first();

            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado'
                ], 404);
            }

            $estadoInfo = $this->determinarEstadoPedido($pedido->Fecha);

            $pedidoCompleto = [
                'id' => $pedido->ID,
                'fecha' => $pedido->Fecha,
                'fecha_formateada' => Carbon::parse($pedido->Fecha)->format('d/m/Y H:i'),
                'precio' => $pedido->Precio,
                'estado' => $estadoInfo,
                'email' => $pedido->email ?? null,
                'direccion' => $pedido->dirección ?? $pedido->direccion ?? null,
                'ciudad' => $pedido->ciudad ?? null,
                'codigo_postal' => $pedido->codigo_postal ?? null,
                'metodo_pago' => $pedido->metodo_pago ?? null,
                'productos' => $pedido->ventas->map(function($venta) {
                    return $this->formatearProductoVenta($venta);
                })
            ];

            return response()->json([
                'success' => true,
                'pedido' => $pedidoCompleto
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo pedido: ' . $e->getMessage(), [
                'pedido_id' => $idPedido,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formatear información de producto en venta para respuesta JSON
     *
     * @param \App\Models\Venta $venta Instancia de venta
     * @return array Datos formateados del producto
     */
    private function formatearProductoVenta($venta)
    {
        $imagenUrl = null;
        if ($venta->producto && $venta->producto->URL) {
            $urlLimpia = rtrim($venta->producto->URL, '/');
            $imagenUrl = '/images/material/' . $urlLimpia . '/Frente.jpg';
        }
        
        $cantidad = $venta->Cantidad ?? $venta->cantidad ?? 0;
        
        return [
            'nombre' => $venta->producto->Nombre ?? 'Producto no encontrado',
            'precio' => $venta->Precio,
            'cantidad' => $cantidad,
            'imagen' => $imagenUrl,
            'subtotal' => $venta->Precio * $cantidad
        ];
    }
}