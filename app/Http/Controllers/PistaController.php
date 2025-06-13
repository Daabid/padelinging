<?php

namespace App\Http\Controllers;
use App\Models\pista;
use Illuminate\Http\Request;

class PistaController extends Controller
{
    public function index()
    {
        $pista = pista::all(); // Obtenemos datos con el modelo
        return response()->json($pista);
    }

    // Coje hel precio de una pista
    public function getPrecio(Request $request)
    {
        $precio = pista::where('IDPista', '=', $request->pista)->get('Precio');
        
        return response()->json($precio);
    }
}
