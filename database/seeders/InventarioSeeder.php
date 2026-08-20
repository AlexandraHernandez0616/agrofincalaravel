<?php

namespace Database\Seeders;

use App\Models\Herramienta;
use App\Models\Insumo;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Herramientas Demo (Bodega 1)
        $herramientas = [
            [
                'id_herramienta' => 1,
                'nombre' => 'Pala Punta Con Mango Anilla ALYCO',
                'cantidad_total' => 5,
                'estado' => 'Disponible',
                'foto_referencia' => null,
                'fecha_registro' => '2026-05-12',
            ],
            [
                'id_herramienta' => 2,
                'nombre' => 'MACHETE 460 PULIDO CACHA PLASTICA ROJA',
                'cantidad_total' => 10,
                'estado' => 'Disponible',
                'foto_referencia' => null,
                'fecha_registro' => '2026-05-14',
            ],
        ];

        foreach ($herramientas as $h) {
            Herramienta::updateOrCreate(['id_herramienta' => $h['id_herramienta']], $h);
        }

        // 2. Insumos Demo (Bodega 2)
        $insumos = [
            [
                'id_insumo' => 2,
                'nombre' => 'AGROCOSECHA Fertilizante para café y cultivos productivos con alto N y K',
                'stock_actual' => 5.00,
                'unidad_medida' => 'arrobas',
                'cantidad_minima' => 2.00,
                'fecha_vencimiento' => '2036-08-16',
                'foto_referencia' => null,
                'fecha_registro' => '2026-05-20',
            ],
        ];

        foreach ($insumos as $i) {
            Insumo::updateOrCreate(['id_insumo' => $i['id_insumo']], $i);
        }
    }
}
