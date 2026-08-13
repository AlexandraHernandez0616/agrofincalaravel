<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('producciones', function (Blueprint $table) {
            $table->integer('id_produccion')->autoIncrement()->primary();
            $table->integer('id_trabajador');
            $table->integer('id_lote');
            $table->date('fecha');
            $table->decimal('cantidad', 10, 2);
            $table->string('unidad_medida', 50);
            $table->foreign('id_trabajador')
                ->references('id_trabajador')
                ->on('trabajadores');
            $table->foreign('id_lote')
                ->references('id_lote')
                ->on('lotes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};
