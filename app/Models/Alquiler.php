<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    protected $table = "alquiler";
    public $timestamps = false;
    protected $fillable = ['ID', 'Usuario', 'FInicio', 'FFinal', 'Precio'];
}
?> 