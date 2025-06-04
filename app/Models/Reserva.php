<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = "Reserva";
    protected $fillable = ['ID', 'Usuario', 'Pista', 'Alquiler', 'FInicio', 'FFinal'];
}

?>