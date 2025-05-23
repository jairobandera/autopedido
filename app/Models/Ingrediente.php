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

    public function detallesQuitados()
    {
        return $this->belongsToMany(
            DetallePedido::class,
            'detalle_pedido_ingrediente_quitado',
            'ingrediente_id',
            'detalle_pedido_id'
        )->withTimestamps();
    }
}