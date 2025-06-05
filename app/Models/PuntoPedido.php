<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoPedido extends Model
{
    protected $table = 'punto_pedido';
    protected $fillable = ['cliente_id', 'pedido_id','cantidad', 'tipo', 'fecha'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
