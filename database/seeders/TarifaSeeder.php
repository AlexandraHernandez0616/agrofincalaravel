<?php

namespace Database\Seeders;

use App\Models\Tarifa;
use Illuminate\Database\Seeder;

class TarifaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tarifa::firstOrCreate(
            [
                'tipo_pago' => 'Producción',
                'fecha_inicio_vigencia' => '2026-05-13',
            ],
            [
                'valor' => 12000.00,
                'fecha_fin_vigencia' => '2026-05-15',
                'activa' => 1,
            ]
        );

        Tarifa::firstOrCreate(
            [
                'tipo_pago' => 'Jornada',
                'fecha_inicio_vigencia' => '2026-05-11',
            ],
            [
                'valor' => 50000.00,
                'fecha_fin_vigencia' => '2026-05-12',
                'activa' => 1,
            ]
        );
    }
}
