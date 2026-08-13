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
        Schema::create('lotes', function (Blueprint $table) {
            $table->integer('id_lote')->autoIncrement()->primary(   );
            $table->integer('id_cultivo');
            $table->string('nombre', 100);
            $table->string('ubicacion_descripcion', 150)->nullable();
            $table->decimal('extension', 10, 2)->nullable();
            $table->date('fecha_registro')->nullable();

            $table->foreign('id_cultivo')
                ->references('id_cultivo')
                ->on('cultivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
