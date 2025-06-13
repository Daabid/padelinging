<?php  
namespace App\Models;  
use Illuminate\Database\Eloquent\Model;  

class Inventario extends Model {     
    protected $table = "Inventario";     
    protected $primaryKey = 'IDProducto';      
    protected $fillable = ['IDProducto', 'Tipo', 'Precio', 'URL', 'Nombre', 'Stock'];
    public $incrementing = true;     
    protected $keyType = 'int'; 
}