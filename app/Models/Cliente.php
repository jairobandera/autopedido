<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'telefono',
        'puntos',
        'activo',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function puntosPedido()
    {
        return $this->hasMany(PuntoPedido::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }


}
