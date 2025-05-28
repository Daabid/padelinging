<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = "Inventario";
    protected $fillable = ['IDProducto', 'Tipo', 'Precio', 'URL'];
}
