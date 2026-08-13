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
        Schema::create('pagos', function (Blueprint $table) {
            $table->integer('id_pago')->autoIncrement()->primary();
            $table->integer('id_liquidacion');
            $table->integer('id_autorizacion')->nullable();
            $table->integer('id_usuario_registrador');
            $table->date('fecha_pago');
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 30)->nullable();
            $table->string('referencia_pago', 100)->nullable();
            $table->text('observacion')->nullable();
            $table->foreign('id_liquidacion')
                ->references('id_liquidacion')
                ->on('liquidaciones');
            $table->foreign('id_autorizacion')
                ->references('id_autorizacion')
                ->on('autorizaciones_delegadas');
            $table->foreign('id_usuario_registrador')
                ->references('id_usuario')
                ->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
