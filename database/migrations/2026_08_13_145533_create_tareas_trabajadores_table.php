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
        Schema::create('tareas_trabajadores', function (Blueprint $table) {
            $table->integer('id_tarea_trabajador')->autoIncrement()->primary();
            $table->integer('id_tarea');
            $table->integer('id_trabajador');
            $table->dateTime('fecha_asignacion')->useCurrent();
            $table->dateTime('fecha_finalizacion')->nullable();
            $table->text('observacion_cierre')->nullable();
            $table->string('evidencia_foto', 255)->nullable();
            $table->string('estado_detalle', 30)->default('ASIGNADA');

            $table->foreign('id_tarea')
                ->references('id_tarea')
                ->on('tareas');
            $table->foreign('id_trabajador')
                ->references('id_trabajador')
                ->on('trabajadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas_trabajadores');
    }
};
