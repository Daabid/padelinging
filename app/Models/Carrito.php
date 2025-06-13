<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Carrito - Representa el carrito de compras de los usuarios
 * 
 * Este modelo maneja los carritos de compra, incluyendo información del usuario,
 * precio total, datos de envío y método de pago. Cada carrito puede tener
 * múltiples ventas asociadas.
 * 
 * @package App\Models
 * @author David
 * @version 1.0
 * 
 * @property string $ID Identificador único del carrito (string, no auto-incremental)
 * @property string $Usuario Nombre o identificador del usuario propietario del carrito
 * @property float $Precio Precio total del carrito
 * @property string $Fecha Fecha de creación o última modificación del carrito
 * @property string $direccion Dirección de envío (sin tilde)
 * @property string $dirección Dirección de envío (con tilde) - posible campo duplicado
 * @property string $ciudad Ciudad de envío
 * @property string $codigo_postal Código postal para la dirección de envío
 * @property string $metodo_pago Método de pago seleccionado (efectivo, tarjeta, etc.)
 * @property string $email Email del usuario (si existe este campo)
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Carrito newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrito newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrito query()
 * 
 * @see \App\Models\Venta
 */
class Carrito extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     * 
     * Se especifica con 'C' mayúscula para coincidir con la foreign key
     * en otras tablas relacionadas.
     * 
     * @var string
     */
    protected $table = 'Carrito';
    
    /**
     * Clave primaria de la tabla
     * 
     * Se define como 'ID' en lugar del 'id' por defecto de Laravel
     * para mantener consistencia con el esquema de base de datos existente.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';
    
    /**
     * Indica si la clave primaria es auto-incremental
     * 
     * Se establece como false porque el ID es de tipo string
     * y se asigna manualmente, no automáticamente.
     * 
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Tipo de dato de la clave primaria
     * 
     * Se especifica como 'string' porque el ID no es numérico
     * sino una cadena de texto.
     * 
     * @var string
     */
    protected $keyType = 'string';
    
    /**
     * Indica si el modelo usa timestamps automáticos
     * 
     * Se establece como false si la tabla no tiene columnas
     * created_at y updated_at. Cambiar a true si las tiene.
     * 
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Campos que deben ser tratados como fechas
     * 
     * Laravel automáticamente convertirá estos campos a instancias
     * de Carbon para facilitar el manejo de fechas.
     * 
     * @var array<string>
     */
    protected $dates = [
        'Fecha',        // Fecha personalizada del carrito
        'created_at',   // Fecha de creación (si existe)
        'updated_at'    // Fecha de última actualización (si existe)
    ];
    
    /**
     * Campos que pueden ser asignados masivamente
     * 
     * Estos campos pueden ser llenados usando create() o fill()
     * de forma segura, protegiendo contra asignación masiva maliciosa.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'ID',               // ID del carrito (asignación manual)
        'Usuario',          // Usuario propietario del carrito
        'Precio',           // Precio total del carrito
        'Fecha',            // Fecha del carrito
        'direccion',        // Dirección de envío (sin tilde)
        'dirección',        // Dirección de envío (con tilde) - posible duplicado
        'ciudad',           // Ciudad de destino
        'codigo_postal',    // Código postal
        'metodo_pago',      // Método de pago elegido
        'email'             // Email del usuario (si existe)
    ];

    /**
     * Relación uno a muchos con el modelo Venta
     * 
     * Un carrito puede tener múltiples ventas asociadas.
     * Cada venta pertenece a un carrito específico.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Venta>
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'Carrito', 'ID');
    }
}