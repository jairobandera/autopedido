<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // Atributos que indicaste:
            $table->string('cedula')->unique();
            $table->string('telefono');
            $table->integer('puntos')->default(0);
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
