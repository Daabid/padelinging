<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alquiler;

class AlquilerController extends Controller
{
    public function index()
    {
        $alquileres = Alquiler::all(); // Obtenemos datos con el modelo
        return response()->json($alquileres);
    }

    public function realizarAlquiler(Request $request)
        {
            $id = Alquiler::max('ID') +1;
            $usuario = $request->Usuario;
            $FInicio = $request->FInicio;
            $FFinal = $request->FFinal; 
            $precio = $request->Precio;

            $alquiler = new Alquiler([
                'ID' => $id, 
                'Usuario' => $usuario, 
                'FInicio' => $FInicio, 
                'FFinal' => $FFinal,
                'Precio' => $precio
            ]);

            $alquiler->save();

            // Opcional: retornar una respuesta
            return response()->json(['message' => 'Alquiler creada exitosamente', 'alquiler' => $alquiler], 201);
        }
}
