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
        Schema::create('herramientas', function (Blueprint $table) {
            $table->integer('id_herramienta')->autoIncrement()->primary();
            $table->string('nombre', 100);
            $table->integer('cantidad_total');
            $table->string('estado', 50)->nullable();
            $table->string('foto_referencia', 255)->nullable();
            $table->date('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('herramienta');
    }
};
