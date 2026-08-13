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
        Schema::create('tareas', function (Blueprint $table) {
            $table->integer('id_tarea')->autoIncrement()->primary();
            $table->integer('id_lote');
            $table->integer('id_mayordomo');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->string('estado_tarea', 30)->default('PENDIENTE');
            $table->foreign('id_lote')
                ->references('id_lote')
                ->on('lotes');
            $table->foreign('id_mayordomo')
                ->references('id_usuario')
                ->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
