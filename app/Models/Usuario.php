<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre', 'contrasena', 'rol', 'activo'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

}
