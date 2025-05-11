<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'imagen', 'activo'];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto');
    }

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'ingrediente_producto')
                    ->withPivot('es_obligatorio');
    }

    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'producto_id');
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }
}