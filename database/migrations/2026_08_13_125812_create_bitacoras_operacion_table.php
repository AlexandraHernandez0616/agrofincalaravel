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
        Schema::create('bitacora_operacion', function (Blueprint $table) {
            $table->integer('id_bitacora')->autoIncrement()->primary();
            $table->integer('id_usuario');
            $table->dateTime('fecha_hora')->useCurrent();
            $table->string('operacion', 100);
            $table->string('modulo', 100);
            $table->text('detalle')->nullable();
            $table->foreign('id_usuario')
                ->references('id_usuario')
        ->on('usuarios');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_operacion');
    }
};
