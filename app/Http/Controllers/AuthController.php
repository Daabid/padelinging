<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin(){
        return view('login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $usuario = \App\Models\Usuario::where('Correo', $credentials['correo'])->first();
        
        //dd($usuario, Hash::check($credentials['password'], $usuario->contraseña));

        if ($usuario && \Illuminate\Support\Facades\Hash::check($credentials['password'], $usuario->Contraseña)) {
            \Illuminate\Support\Facades\Auth::login($usuario);
            return redirect('/'); // o cualquier página que uses como inicio
        }

        return back()->withErrors([
            'correo' => 'Correo o contraseña incorrectos.',
        ]);
    }

    public function register(Request $request){
    $validated = $request->validate([
        'dni' => 'required|string|unique:Usuario,DNI',
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'correo' => 'required|email|unique:Usuario,Correo',
        'fecha_nacimiento' => 'required|date',
        'password' => 'required|string|min:6',
        'rol' => 'required|string',
    ]);

    $usuario = new Usuario([
        'DNI' => $validated['dni'],
        'Nombre' => $validated['nombre'],
        'Apellido' => $validated['apellido'],
        'Correo' => $validated['correo'],
        'FechaNacimiento' => $validated['fecha_nacimiento'],
        'Contraseña' => Hash::make($validated['password']),
        'ROL' => $validated['rol'],
    ]);

    $usuario->save();

    // Login automático tras registro
    Auth::login($usuario);

    return redirect('/');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

