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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id_usuario')->autoIncrement()->primary();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('documento', 50)->unique();
            $table->string('telefono', 30)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password_hash', 255);
            $table->string('rol', 20)->comment('ADMINISTRADOR, MAYORDOMO, TRABAJADOR');
            $table->boolean('activo')->default(true);
            $table->dateTime('fecha_creacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
