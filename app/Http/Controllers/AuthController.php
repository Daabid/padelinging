<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

/**
 * Controlador de autenticación para manejo de login, registro y logout de usuarios
 * 
 * Este controlador maneja todas las operaciones relacionadas con la autenticación
 * de usuarios incluyendo el proceso de login, registro de nuevos usuarios y logout.
 * 
 * @package App\Http\Controllers
 * @author David
 * @version 1.0
 */
class AuthController extends Controller
{
    /**
     * Muestra la vista de login
     * 
     * Retorna la vista del formulario de inicio de sesión para que el usuario
     * pueda ingresar sus credenciales.
     * 
     * @return \Illuminate\Contracts\View\View Vista del formulario de login
     */
    public function showLogin(){
        return view('login');
    }

    /**
     * Procesa el intento de login del usuario
     * 
     * Valida las credenciales del usuario, verifica la contraseña hasheada,
     * realiza el login y redirige al calendario en caso de éxito.
     * 
     * @param \Illuminate\Http\Request $request Petición HTTP con credenciales del usuario
     * @return \Illuminate\Http\RedirectResponse Redirección al calendario o de vuelta al login con errores
     * 
     * @throws \Illuminate\Validation\ValidationException Si las credenciales no son válidas
     */
    public function login(Request $request)
    {
        session()->forget('url.intended');
        
        // Validar credenciales de entrada
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Debug: Verificar que lleguen los datos
        Log::info('Intento de login', [
            'correo' => $credentials['correo'],
            'password_presente' => !empty($credentials['password'])
        ]);

        // Buscar usuario por correo electrónico
        $usuario = Usuario::where('Correo', $credentials['correo'])->first();
        
        if (!$usuario) {
            Log::warning('Usuario no encontrado', ['correo' => $credentials['correo']]);
            return back()->withErrors(['correo' => 'Usuario no encontrado']);
        }

        // Debug: Usuario encontrado
        Log::info('Usuario encontrado', [
            'dni' => $usuario->DNI,
            'correo' => $usuario->Correo,
            'password_hash' => substr($usuario->Contraseña, 0, 10) . '...'
        ]);

        // Verificar contraseña hasheada
        if (!Hash::check($credentials['password'], $usuario->Contraseña)) {
            Log::warning('Contraseña incorrecta para usuario', ['correo' => $credentials['correo']]);
            return back()->withErrors(['password' => 'Contraseña incorrecta']);
        }

        Log::info('Contraseña verificada correctamente');

        // Realizar login manual del usuario
        Auth::login($usuario);
        
        // Debug: Verificar si el login fue exitoso
        if (Auth::check()) {
            Log::info('Login exitoso', [
                'user_id' => Auth::id(),
                'user_correo' => Auth::user()->Correo
            ]);
        } else {
            Log::error('Login falló - Auth::check() retorna false');
            return back()->withErrors(['general' => 'Error en el sistema de autenticación']);
        }

        // Regenerar sesión por seguridad
        $request->session()->regenerate();
        
        Log::info('Sesión regenerada, redirigiendo a calendario');
        return redirect('/calendario');
    }

    /**
     * Registra un nuevo usuario en el sistema
     * 
     * Valida los datos del usuario, crea un nuevo registro en la base de datos
     * con la contraseña hasheada y realiza login automático.
     * 
     * @param \Illuminate\Http\Request $request Petición HTTP con datos del nuevo usuario
     * @return \Illuminate\Http\RedirectResponse Redirección al catálogo tras registro exitoso
     * 
     * @throws \Illuminate\Validation\ValidationException Si los datos no son válidos
     */
    public function register(Request $request){
        // Validar datos de entrada
        $validated = $request->validate([
            'dni' => 'required|string|unique:Usuario,DNI',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|unique:Usuario,Correo',
            'fecha_nacimiento' => 'required|date',
            'password' => 'required|string|min:6',
            'rol' => 'required|string',
        ]);

        // Crear nuevo usuario con contraseña hasheada
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

        // Debug registro
        Log::info('Usuario registrado', ['dni' => $usuario->DNI, 'correo' => $usuario->Correo]);

        // Login automático tras registro exitoso
        Auth::login($usuario);
        
        if (Auth::check()) {
            Log::info('Auto-login después de registro exitoso');
        } else {
            Log::error('Auto-login después de registro falló');
        }
        
        // Regenerar sesión por seguridad
        $request->session()->regenerate();
        return redirect('/catalogo');
    }

    /**
     * Cierra la sesión del usuario actual
     * 
     * Realiza logout del usuario, invalida la sesión actual y regenera
     * el token CSRF por seguridad.
     * 
     * @param \Illuminate\Http\Request $request Petición HTTP actual
     * @return \Illuminate\Http\RedirectResponse Redirección a la página de login
     */
    public function logout(Request $request)
    {
        Log::info('Logout iniciado para usuario', ['user_id' => Auth::id()]);
        
        // Cerrar sesión del usuario
        Auth::logout();
        
        // Invalidar sesión actual
        $request->session()->invalidate();
        
        // Regenerar token CSRF
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}