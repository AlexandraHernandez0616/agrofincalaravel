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
        Schema::create('detalles_prestamos', function (Blueprint $table) {
        $table->integer('id_detalle_prestamo')->autoIncrement()->primary();
        $table->integer('id_prestamo');
        $table->integer('id_herramienta');
        $table->integer('cantidad');
        $table->integer('cantidad_devuelta')->default(0);
        $table->date('fecha_entrega')->nullable();
        $table->date('fecha_devolucion')->nullable();
        $table->string('estado_devolucion', 50)->nullable();
        $table->text('observacion')->nullable();
        $table->integer('recibido_por')->nullable();
        $table->foreign('id_prestamo')
            ->references('id_prestamo')
            ->on('prestamos');
        $table->foreign('id_herramienta')
            ->references('id_herramienta')
            ->on('herramientas');
        $table->foreign('recibido_por')
        ->references('id_usuario')
        ->on('usuarios');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_prestamos');
    }
};
