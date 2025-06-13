<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = "Reserva";
     public $timestamps = false;
    protected $fillable = ['ID', 'Usuario', 'Pista', 'Alquiler', 'FInicio', 'FFinal'];
}

?>