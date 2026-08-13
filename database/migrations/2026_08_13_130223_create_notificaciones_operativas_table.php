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
        Schema::create('notificaciones_operativas', function (Blueprint $table) {
            $table->integer('id_notificacion')->autoIncrement()->primary();
            $table->integer('id_usuario_destino');
            $table->string('tipo', 50);
            $table->string('mensaje', 255);
            $table->dateTime('fecha_hora')->useCurrent();
            $table->tinyInteger('leida')->default(0);
            $table->foreign('id_usuario_destino')
                ->references('id_usuario')
                ->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_operativas');
    }
};
