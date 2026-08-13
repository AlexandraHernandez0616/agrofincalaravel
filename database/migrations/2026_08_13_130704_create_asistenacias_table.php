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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->integer('id_asistencia')->autoIncrement()->primary();

            $table->integer('id_trabajador');

            $table->date('fecha');

            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();

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
        Schema::dropIfExists('asistencias');
    }
};
