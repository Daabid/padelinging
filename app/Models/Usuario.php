<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'Usuario'; // nombre exacto de tu tabla

    protected $primaryKey = 'DNI'; // si el campo dni es clave primaria

    public $incrementing = false; // importante si el dni no es un ID autoincremental
    protected $keyType = 'string'; // o 'int' si el dni es numérico

    protected $fillable = [
        'DNI', 'Nombre', 'Apellido', 'Correo', 'FechaNacimiento', 'Contraseña', 'ROL',
    ];

    protected $hidden = [
        'contraseña',
    ];

    public function getAuthPassword()
    {
        return $this->contraseña; // importante: Laravel buscará esta columna para verificar la contraseña
    }
}
