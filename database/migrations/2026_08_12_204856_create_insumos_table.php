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
        Schema::create('insumos', function (Blueprint $table) {
            $table->integer('id_insumo')->autoIncrement()->primary();
            $table->string('nombre', 100);
            $table->decimal('stock_actual', 10, 2);
            $table->string('unidad_medida', 50)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('cantidad_minima', 10, 2);
            $table->string('foto_referencia', 255)->nullable();
            $table->date('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
