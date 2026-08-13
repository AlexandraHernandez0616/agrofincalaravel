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
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->integer('id_liquidacion')->autoIncrement()->primary();
            $table->integer('id_trabajador');
            $table->integer('id_tarifa');
            $table->integer('id_autorizacion')->nullable();
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->decimal('jornadas_consideradas', 10, 2)->default(0.00);
            $table->decimal('produccion_considerada', 10, 2)->default(0.00);
            $table->decimal('valor_calculado', 10, 2);
            $table->date('fecha_generacion');
            $table->date('fecha_liquidacion')->nullable();
            $table->string('estado', 30)->default('PENDIENTE');
            $table->text('observacion')->nullable();
            $table->foreign('id_trabajador')
                ->references('id_trabajador')
                ->on('trabajadores');

            $table->foreign('id_tarifa')
                ->references('id_tarifa')
                ->on('tarifas');

            $table->foreign('id_autorizacion')
        ->references('id_autorizacion')
        ->on('autorizaciones_delegadas');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};
