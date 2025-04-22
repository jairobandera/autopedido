<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['pedido_id', 'tipo', 'monto', 'fecha', 'estado'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
