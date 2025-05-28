<?php

namespace App\Http\Controllers;
    use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Inventario::all(); // Obtenemos datos con el modelo
        return view('carruselProductos', compact('productos'));
    }
}
