<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PuntoPedido;  // <-- importa PuntoPedido aquí
use App\Models\Pago;
use App\Models\Usuario;
use App\Models\DetallePedido;
use App\Models\Cliente;

class Pedido extends Model
{
    protected $fillable = ['usuario_id', 'total', 'metodo_pago', 'estado', 'codigo'];

    public function pago()
    {
        return $this->hasOne(Pago::class, 'pedido_id');
    }


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function puntoPedido()
    {
        return $this->hasOne(PuntoPedido::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function clientes()
    {
        return $this->belongsToMany(
            Cliente::class,
            'punto_pedido',
            'pedido_id',
            'cliente_id'
        )
            ->withPivot(['cantidad', 'tipo', 'fecha'])
            ->withTimestamps();
    }




}
