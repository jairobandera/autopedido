<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    protected $fillable = ['nombre', 'contrasena', 'rol', 'activo'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

    public function setContrasenaAttribute($value)
{
    // Solo hashear si no lo está ya
    if (!Hash::needsRehash($value)) {
        $this->attributes['contrasena'] = $value;
    } else {
        $this->attributes['contrasena'] = Hash::make($value);
    }
}

}