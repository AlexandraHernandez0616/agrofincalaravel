<?php

namespace Database\Seeders;

use App\Models\Cultivo;
use App\Models\Lote;
use Illuminate\Database\Seeder;

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cultivo Café
        $cultivoCafe = Cultivo::firstOrCreate(
            ['nombre' => 'cafe'],
            [
                'variedad' => 'Castillo / Arábica',
                'cantidad_cultivo' => 5.00,
                'fecha_registro' => '2026-05-12',
                'estado' => 'ACTIVO',
            ]
        );

        // 2. Cultivo Cacao
        $cultivoCacao = Cultivo::firstOrCreate(
            ['nombre' => 'cacao'],
            [
                'variedad' => 'CCN-51 / Fino de Aroma',
                'cantidad_cultivo' => 2.00,
                'fecha_registro' => '2026-05-14',
                'estado' => 'ACTIVO',
            ]
        );

        // 3. Lote Norte
        Lote::firstOrCreate(
            ['nombre' => 'norte'],
            [
                'id_cultivo' => $cultivoCafe->id_cultivo,
                'ubicacion_descripcion' => 'zona A',
                'extension' => 5.00,
                'fecha_registro' => '2026-05-12',
            ]
        );

        // 4. Lote Sur
        Lote::firstOrCreate(
            ['nombre' => 'sur'],
            [
                'id_cultivo' => $cultivoCacao->id_cultivo,
                'ubicacion_descripcion' => 'zona b',
                'extension' => 2.00,
                'fecha_registro' => '2026-05-14',
            ]
        );
    }
}
