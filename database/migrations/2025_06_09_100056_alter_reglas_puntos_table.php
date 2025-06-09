<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reglas_puntos', function (Blueprint $table) {
            // 1) Renombrar monto_base a monto_min
            $table->renameColumn('monto_base', 'monto_min');

            // 2) Agregar monto_max
            $table->decimal('monto_max', 10, 2)->after('monto_min');

            // 3) Quitar columnas de mínimos/máximos de puntos
            $table->dropColumn(['minimo_puntos', 'maximo_puntos']);
        });
    }

    public function down()
    {
        Schema::table('reglas_puntos', function (Blueprint $table) {
            // Para revertir:
            $table->renameColumn('monto_min', 'monto_base');
            $table->dropColumn('monto_max');
            $table->unsignedInteger('minimo_puntos')->nullable();
            $table->unsignedInteger('maximo_puntos')->nullable();
        });
    }
};

