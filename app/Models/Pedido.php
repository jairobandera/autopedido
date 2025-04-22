<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['usuario_id', 'total', 'metodo_pago', 'estado', 'codigo'];

    public function pago()
    {
        return $this->hasOne(Pago::class, 'pedido_id');
    }


    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function puntosPedidos()
    {
        return $this->hasMany(PuntoPedido::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }




}
