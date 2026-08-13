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
        Schema::create('autorizaciones_delegadas', function (Blueprint $table) {
            $table->integer('id_autorizacion')->autoIncrement()->primary();
            $table->integer('id_administrador');
            $table->integer('id_mayordomo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('acciones_permitidas', 255);
            $table->decimal('monto_maximo', 10, 2)->nullable();
            $table->string('estado', 30)->default('ACTIVA');
            $table->foreign('id_administrador')
                ->references('id_usuario')
                ->on('usuarios');

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
        Schema::dropIfExists('autorizaciones_delegadas');
    }
};
