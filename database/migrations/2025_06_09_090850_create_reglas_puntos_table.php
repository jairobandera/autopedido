<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reglas_puntos', function (Blueprint $table) {
            $table->id();
            // Cada X unidades monetarias
            $table->decimal('monto_base', 10, 2);
            // Y puntos generados por cada unidad de monto_base
            $table->unsignedInteger('puntos_base');
            // Límite mínimo de puntos a otorgar
            $table->unsignedInteger('minimo_puntos')->nullable();
            // Límite máximo de puntos a otorgar
            $table->unsignedInteger('maximo_puntos')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reglas_puntos');
    }
};

