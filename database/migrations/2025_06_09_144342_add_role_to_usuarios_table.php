<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifica el ENUM para añadir 'Cliente'
        DB::statement("
            ALTER TABLE `usuarios`
            MODIFY `rol` ENUM('Administrador','Cajero','Cocina','Cliente') NOT NULL
        ");
    }


    /**
     * Reverse the migrations.
     */
 public function down(): void
    {
        // Vuelve al ENUM original sin 'Cliente'
        DB::statement("
            ALTER TABLE `usuarios`
            MODIFY `rol` ENUM('Administrador','Cajero','Cocina') NOT NULL
        ");
    }
};
