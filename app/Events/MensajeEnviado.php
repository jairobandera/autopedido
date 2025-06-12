<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class MensajeEnviado implements ShouldBroadcast
{
    public $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }
}
