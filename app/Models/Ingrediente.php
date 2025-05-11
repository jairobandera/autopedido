<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'activo'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'ingrediente_producto');
    }
}