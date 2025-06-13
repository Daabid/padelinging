<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Usuario que extiende de Authenticatable para manejo de autenticación
 * 
 * Este modelo representa a los usuarios del sistema con autenticación personalizada
 * utilizando DNI como clave primaria en lugar del ID tradicional.
 * 
 * @package App\Models
 * @author David
 * @version 1.0
 * 
 * @property string $DNI Documento Nacional de Identidad (Clave primaria)
 * @property string $Nombre Nombre del usuario
 * @property string $Apellido Apellido del usuario
 * @property string $Correo Correo electrónico del usuario
 * @property string $FechaNacimiento Fecha de nacimiento del usuario
 * @property string $Contraseña Contraseña encriptada del usuario
 * @property string $ROL Rol del usuario en el sistema
 */
class Usuario extends Authenticatable
{
    use Notifiable;

    /**
     * Indica si el modelo debe usar timestamps automáticos (created_at, updated_at)
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Nombre de la tabla en la base de datos
     * 
     * @var string
     */
    protected $table = 'Usuario';

    /**
     * Clave primaria de la tabla
     * 
     * @var string
     */
    protected $primaryKey = 'DNI';

    /**
     * Indica si la clave primaria es auto-incremental
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipo de dato de la clave primaria
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Atributos que pueden ser asignados masivamente
     * 
     * @var array<string>
     */
    protected $fillable = [
        'DNI', 'Nombre', 'Apellido', 'Correo', 'FechaNacimiento', 'Contraseña', 'ROL',
    ];

    /**
     * Atributos que deben ser ocultados en arrays y JSON
     * 
     * @var array<string>
     */
    protected $hidden = [
        'Contraseña',
    ];

    /**
     * Obtiene la contraseña para autenticación
     * 
     * Método requerido por Laravel para el sistema de autenticación.
     * Retorna el campo que contiene la contraseña encriptada.
     * 
     * @return string La contraseña encriptada del usuario
     */
    public function getAuthPassword()
    {
        return $this->Contraseña;
    }

    /**
     * Obtiene el nombre del identificador de autenticación
     * 
     * Método requerido por Laravel para especificar qué campo usar
     * como identificador único para autenticación (por defecto sería 'id').
     * 
     * @return string El nombre del campo identificador ('DNI')
     */
    public function getAuthIdentifierName()
    {
        return 'DNI'; // Clave primaria
    }

    /**
     * Obtiene el valor del identificador de autenticación
     * 
     * Método requerido por Laravel para obtener el valor del identificador
     * único del usuario actual.
     * 
     * @return string El valor del DNI del usuario
     */
    public function getAuthIdentifier()
    {
        return $this->DNI;
    }

    /**
     * Encuentra un usuario por nombre de usuario para Passport
     * 
     * Método utilizado por Laravel Passport para encontrar usuarios
     * durante el proceso de autenticación OAuth.
     * 
     * @param string $username El correo electrónico del usuario
     * @return \App\Models\Usuario|null El usuario encontrado o null si no existe
     */
    public function findForPassport($username)
    {
        return $this->where('Correo', $username)->first();
    }

    /**
     * Encuentra un usuario por su correo electrónico
     * 
     * Método estático personalizado para buscar usuarios por correo.
     * Útil para procesos de login y verificación de usuarios existentes.
     * 
     * @param string $email El correo electrónico a buscar
     * @return \App\Models\Usuario|null El usuario encontrado o null si no existe
     */
    public static function findByEmail($email)
    {
        return static::where('Correo', $email)->first();
    }
}