<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaPunto extends Model
{
    use HasFactory;

    protected $table = 'reglas_puntos';

    // Actualiza el fillable quitando los límites y usando los tramos:
    protected $fillable = [
        'monto_min',
        'monto_max',
        'puntos_base',
    ];

    /**
     * Obtener todas las reglas ordenadas de más reciente a más antigua
     */
    public static function allRecientes()
    {
        return static::orderBy('created_at', 'desc')->get();
    }

    /**
     * Encontrar el tramo que cubre un total dado
     */
    public static function findByTotal(float $total)
    {
        return static::where('monto_min', '<=', $total)
            ->where('monto_max', '>=', $total)
            ->first();
    }
}

