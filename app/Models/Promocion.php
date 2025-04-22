<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'descuento', 'fecha_inicio', 'fecha_fin', 'activo'];

    // Dentro de la clase Promocion
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

}
