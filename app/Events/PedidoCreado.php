<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class PedidoCreado implements ShouldBroadcastNow
{
    use SerializesModels;

    public $pedido; // recibirá la modelo completa

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido->load(
            'detalles.producto',
            'detalles.ingredientesQuitados',
            'cliente',
            'pago'
        );
    }

    public function broadcastOn()
    {
        // Canal público; si quieres privado, usa PrivateChannel
        return new Channel('pedidos');
    }

    public function broadcastWith(): array
    {
        // Asegúrate de haber cargado las relaciones en el constructor:
        // $this->pedido = $pedido->load('detalles.producto', 'detalles.ingredientesQuitados', 'cliente');

        $detalles = $this->pedido->detalles->map(function ($detalle) {
            return [
                'producto' => [
                    'nombre' => $detalle->producto->nombre,
                ],
                'cantidad' => $detalle->cantidad,
                'subtotal' => $detalle->subtotal,
                'ingredientes_quitados' => $detalle
                    ->ingredientesQuitados
                    ->map(fn($i) => ['nombre' => $i->nombre])
                    ->all(),
            ];
        })->all();

        return [
            'id' => $this->pedido->id,
            'origen' => $this->pedido->origen,
            'metodo_pago' => $this->pedido->metodo_pago,
            'codigo' => $this->pedido->codigo,
            'monto' => $this->pedido->total,
            'estado' => $this->pedido->estado,
            'estado_pago' => optional($this->pedido->pago)->estado ?: null,
            'cliente' => [
                'id' => optional($this->pedido->cliente)->id,
                'nombre' => optional($this->pedido->cliente)->nombre,
            ],
            'created_at' => $this->pedido->created_at->toDateTimeString(),
            'detalles' => $detalles,
        ];
    }

}
