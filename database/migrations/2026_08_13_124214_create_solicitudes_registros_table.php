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
        Schema::create('solicitudes_registros', function (Blueprint $table) {
            $table->integer('id_solicitud')->autoIncrement()->primary();

            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('documento', 50);
            $table->string('telefono', 30);

            $table->string('eps', 100)->nullable();
            $table->string('rh', 10)->nullable();

            $table->string('username', 50);
            $table->string('password_hash', 255);

            $table->string('estado', 20)->default('PENDIENTE');

            $table->dateTime('fecha_solicitud')->useCurrent();
            $table->dateTime('fecha_gestion')->nullable();

            $table->integer('id_mayordomo')->nullable();

            $table->text('observacion')->nullable();

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
        Schema::dropIfExists('solicitudes_registros');
    }
};
