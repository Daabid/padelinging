<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;

/**
 * Controlador para la gestión de productos del catálogo
 * 
 * Este controlador maneja la visualización de productos disponibles
 * para venta y la visualización de detalles de productos individuales.
 * 
 * @package App\Http\Controllers
 * @author David
 * @version 1.0
 */
class ProductController extends Controller
{
    /**
     * Muestra el listado de productos disponibles para venta
     * 
     * Obtiene todos los productos del inventario que tienen estado 'Venta'
     * y los envía a la vista del catálogo para su visualización.
     * 
     * @return \Illuminate\View\View Vista del catálogo con los productos disponibles
     * 
     * @throws \Exception Si hay un error al acceder a la base de datos
     * 
     * @example
     * // Uso típico en una ruta
     * Route::get('/catalogo', [ProductController::class, 'index']);
     */
    public function index()
    {
        // Solo productos con estado 'Venta'
        $productos = Inventario::where('estado', 'Venta')->get();

        return view('catalogo', compact('productos'));
    }

    /**
     * Muestra los detalles de un producto específico
     * 
     * Busca un producto por su ID en el inventario y muestra
     * su información detallada en una vista individual.
     * 
     * @param int $id El ID único del producto a mostrar
     * 
     * @return \Illuminate\View\View Vista del producto con sus detalles
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el producto no existe
     */
    public function show($id)
    {
        $producto = Inventario::findOrFail($id);
        return view('producto', compact('producto'));
    }
}