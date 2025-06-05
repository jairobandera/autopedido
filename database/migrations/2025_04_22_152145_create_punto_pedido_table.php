<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntoPedidoTable extends Migration
{
    public function up()
    {
        Schema::create('punto_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            // FK al pedido:
            $table->foreignId('pedido_id')
                ->constrained('pedidos')
                ->onDelete('cascade');

            // Campos extra:
            $table->integer('cantidad')->nullable();;

            // Enum para el tipo (según tu diagrama, usaste "Canjeo" o "Redencion"):
            $table->enum('tipo', ['Canjeo', 'Redencion']);

            // Fecha en la que se registra el punto sobre ese pedido:
            $table->dateTime('fecha');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('punto_pedido');
    }
}

