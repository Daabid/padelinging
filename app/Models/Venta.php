<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Venta
 * 
 * Representa una venta en el sistema, conteniendo información sobre
 * el producto vendido, carrito asociado, precio y cantidad.
 * 
 * @package App\Models
 * @author David
 * @version 1.0
 * 
 * @property int $ID Identificador único de la venta
 * @property int $Producto ID del producto vendido
 * @property int $Carrito ID del carrito asociado
 * @property float $Precio Precio de la venta
 * @property int $cantidad Cantidad del producto (minúscula)
 * @property int $Cantidad Cantidad del producto (mayúscula)
 * 
 * @property-read Inventario $producto Relación con el modelo Inventario
 * @property-read Carrito $carrito Relación con el modelo Carrito
 */
class Venta extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     * 
     * @var string
     */
    protected $table = 'Venta';
    
    /**
     * Clave primaria de la tabla
     * Descomentá esta línea si tu tabla tiene una clave primaria diferente a 'id'
     * 
     * @var string
     */
    // protected $primaryKey = 'ID';
    
    /**
     * Indica si el modelo debe gestionar timestamps automáticamente
     * Desactivado porque la tabla no tiene campos created_at y updated_at
     * 
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Campos que se pueden asignar masivamente
     * 
     * @var array<string>
     */
    protected $fillable = [
        'ID',
        'Producto',
        'Carrito', 
        'Precio',
        'cantidad',    // minúscula
        'Cantidad'     // Primera letra mayúscula (más probable)
    ];

    /**
     * Relación belongsTo con el modelo Inventario
     * 
     * Una venta pertenece a un producto del inventario
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Inventario, Venta>
     */
    public function producto()
    {
        return $this->belongsTo(Inventario::class, 'Producto', 'IDProducto');
    }

    /**
     * Relación belongsTo con el modelo Carrito
     * 
     * Una venta pertenece a un carrito específico
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Carrito, Venta>
     */
    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'Carrito', 'ID');
    }
}