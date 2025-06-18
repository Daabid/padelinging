<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    protected $table = "Alquiler";
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['ID', 'Usuario', 'FInicio', 'FFinal', 'Precio'];
}
?> 