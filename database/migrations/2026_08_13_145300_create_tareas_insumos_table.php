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
        Schema::create('tareas_insumos', function (Blueprint $table) {
            $table->integer('id_tarea_insumo')->autoIncrement()->primary();
            $table->integer('id_tarea');
            $table->integer('id_insumo');
            $table->decimal('cantidad_asignada', 10, 2);
            $table->decimal('cantidad_consumida', 10, 2)->default(0.00);
            $table->decimal('cantidad_reintegrada', 10, 2)->default(0.00);
            $table->foreign('id_tarea')
                    ->references('id_tarea')
                    ->on('tareas');
            $table->foreign('id_insumo')
                ->references('id_insumo')
                ->on('insumos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas_insumos');
    }
};
