<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'Inventario';
    protected $primaryKey = 'IDProducto';

    public $incrementing = true;
    protected $keyType = 'int';
}
