<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNombreApellidoToClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Agregamos columnas nombre y apellido antes de 'puntos'.
            // Ajusta la posición si quieres que queden en otra parte.
            $table->string('nombre')->after('id');
            $table->string('apellido')->after('nombre');
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellido']);
        });
    }
}
