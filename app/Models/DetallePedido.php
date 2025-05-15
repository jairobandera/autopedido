<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ingrediente;

class DetallePedido extends Model
{
    //protected $fillable = ['pedido_id', 'producto_id', 'fecha_hora', 'cantidad', 'subtotal', 'ingrediente_id'];
    protected $table = 'detalle_pedido';
    protected $guarded = [];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Relación many-to-many a ingredientes quitados
    public function ingredientesQuitados()
    {
        return $this->belongsToMany(
            Ingrediente::class,
            'detalle_pedido_ingrediente_quitado',
            'detalle_pedido_id',
            'ingrediente_id'
        )->withTimestamps();
    }

}
