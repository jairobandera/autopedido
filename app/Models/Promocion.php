<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';
    protected $fillable = [
        'nombre',
        'descuento',
        'codigo',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
        'descuento' => 'decimal:2',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_promocion');
    }
}
