<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class PedidoEstadoActualizado implements ShouldBroadcastNow
{
    use SerializesModels;

    public $id;
    public $estado;

    public function __construct(Pedido $pedido)
    {
        $this->id = $pedido->id;
        $this->estado = $pedido->estado;
    }

    public function broadcastOn()
    {
        return new Channel('pedidos');
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'estado' => $this->estado,
        ];
    }
}

