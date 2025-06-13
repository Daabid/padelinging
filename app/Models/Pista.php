<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pista extends Model
{
    protected $table = "Pista";
    protected $fillable = ['IDPista', 'Tipo', 'Superficie', 'Estado', 'Precio'];

    public function getPrecioPista()
    {
        return $this->Precio;
    }
}
?> 