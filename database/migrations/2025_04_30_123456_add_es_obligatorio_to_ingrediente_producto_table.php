<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEsObligatorioToIngredienteProductoTable extends Migration
{
    public function up()
    {
        Schema::table('ingrediente_producto', function (Blueprint $table) {
            $table->boolean('es_obligatorio')->default(0)->after('ingrediente_id');
        });
    }

    public function down()
    {
        Schema::table('ingrediente_producto', function (Blueprint $table) {
            $table->dropColumn('es_obligatorio');
        });
    }
}