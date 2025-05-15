<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidoIngredienteQuitadoTable extends Migration
{
    public function up()
    {
        Schema::create('detalle_pedido_ingrediente_quitado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_pedido_id')
                ->constrained('detalle_pedido')
                ->onDelete('cascade');
            $table->foreignId('ingrediente_id')
                ->constrained('ingredientes')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['detalle_pedido_id', 'ingrediente_id'], 'dp_ing_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_pedido_ingrediente_quitado');
    }
}
