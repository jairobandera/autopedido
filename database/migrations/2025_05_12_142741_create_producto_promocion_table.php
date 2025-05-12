<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('producto_promocion', function (Blueprint $table) {
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->foreignId('promocion_id')->constrained('promociones')->onDelete('cascade');
            $table->timestamps();
            $table->primary(['producto_id', 'promocion_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_promocion');
    }
};
