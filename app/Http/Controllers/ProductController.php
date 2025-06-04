<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;


class ProductController extends Controller
{
    public function index()
    {
        // Solo productos con estado 'Venta'
        $productos = Inventario::where('estado', 'Venta')->get();

        return view('catalogo', compact('productos'));
    }

    public function show($id)
    {
        $producto = Inventario::findOrFail($id);
        return view('producto', compact('producto'));
    }

}