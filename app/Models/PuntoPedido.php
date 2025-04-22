<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoPedido extends Model
{
    protected $fillable = ['cliente_id', 'pedido_id', 'puntos', 'tipo', 'fecha'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
