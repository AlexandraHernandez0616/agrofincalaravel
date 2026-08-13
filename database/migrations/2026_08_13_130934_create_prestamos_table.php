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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->integer('id_prestamo')->autoIncrement()->primary();

            $table->integer('id_trabajador');
            $table->integer('id_mayordomo');
            $table->date('fecha_solicitud');
            $table->date('fecha_aprobacion')->nullable();
            $table->string('estado_prestamo', 30)->default('PENDIENTE');
            $table->text('observacion')->nullable();
            $table->foreign('id_trabajador')
                ->references('id_trabajador')
                ->on('trabajadores');

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
        Schema::dropIfExists('prestamos');
    }
};
