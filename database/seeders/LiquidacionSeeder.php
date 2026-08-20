<?php

namespace Database\Seeders;

use App\Models\Liquidacion;
use App\Models\Tarifa;
use App\Models\Trabajador;
use Illuminate\Database\Seeder;

class LiquidacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener tarifas
        $tarifaJornada = Tarifa::where('tipo_pago', 'like', '%Jornada%')->first();
        $tarifaProduccion = Tarifa::where('tipo_pago', 'like', '%Producción%')->first();

        // Obtener trabajadores por nombre
        $fani = Trabajador::whereHas('usuario', fn($q) => $q->where('nombres', 'like', '%fani%'))->first();
        $mayra = Trabajador::whereHas('usuario', fn($q) => $q->where('nombres', 'like', '%Mayra%'))->first();
        $paola = Trabajador::whereHas('usuario', fn($q) => $q->where('nombres', 'like', '%paola%'))->first();
        $karen = Trabajador::whereHas('usuario', fn($q) => $q->where('nombres', 'like', '%Karen%'))->first();

        // Fallback si no encuentra específicos
        $fallbackWorker = Trabajador::first();

        // 1. Fani camacho - Jornada - 2026-05-14 a 2026-05-14 - 2 Jornadas - $100,000 - Generada
        Liquidacion::firstOrCreate(
            [
                'id_trabajador' => $fani ? $fani->id_trabajador : ($fallbackWorker->id_trabajador ?? 1),
                'periodo_inicio' => '2026-05-14',
                'periodo_fin' => '2026-05-14',
            ],
            [
                'id_tarifa' => $tarifaJornada ? $tarifaJornada->id_tarifa : 1,
                'jornadas_consideradas' => 2.00,
                'produccion_considerada' => 0.00,
                'valor_calculado' => 100000.00,
                'fecha_generacion' => '2026-05-14',
                'fecha_liquidacion' => null,
                'estado' => 'GENERADA',
                'observacion' => 'Liquidación de 2 jornadas generada para pago.',
            ]
        );

        // 2. Mayra pascuas - Producción - 2026-05-13 a 2026-05-13 - 1 Jornada (12 kg) - $144,000 - Pendiente
        Liquidacion::firstOrCreate(
            [
                'id_trabajador' => $mayra ? $mayra->id_trabajador : ($fallbackWorker->id_trabajador ?? 1),
                'periodo_inicio' => '2026-05-13',
                'periodo_fin' => '2026-05-13',
            ],
            [
                'id_tarifa' => $tarifaProduccion ? $tarifaProduccion->id_tarifa : 1,
                'jornadas_consideradas' => 1.00,
                'produccion_considerada' => 12.00,
                'valor_calculado' => 144000.00,
                'fecha_generacion' => '2026-05-13',
                'fecha_liquidacion' => null,
                'estado' => 'PENDIENTE',
                'observacion' => 'Liquidación por cosecha de café en espera de validación.',
            ]
        );

        // 3. Paola garcia - Jornada - 2026-05-12 a 2026-05-13 - 1 Jornada - $50,000 - Generada
        Liquidacion::firstOrCreate(
            [
                'id_trabajador' => $paola ? $paola->id_trabajador : ($fallbackWorker->id_trabajador ?? 1),
                'periodo_inicio' => '2026-05-12',
                'periodo_fin' => '2026-05-13',
            ],
            [
                'id_tarifa' => $tarifaJornada ? $tarifaJornada->id_tarifa : 1,
                'jornadas_consideradas' => 1.00,
                'produccion_considerada' => 0.00,
                'valor_calculado' => 50000.00,
                'fecha_generacion' => '2026-05-12',
                'fecha_liquidacion' => null,
                'estado' => 'GENERADA',
                'observacion' => 'Jornada de mantenimiento.',
            ]
        );

        // 4. Paola garcia - Jornada - 2026-05-11 a 2026-05-11 - 2 Jornadas - $100,000 - Liquidada
        Liquidacion::firstOrCreate(
            [
                'id_trabajador' => $paola ? $paola->id_trabajador : ($fallbackWorker->id_trabajador ?? 1),
                'periodo_inicio' => '2026-05-11',
                'periodo_fin' => '2026-05-11',
                'estado' => 'LIQUIDADA',
            ],
            [
                'id_tarifa' => $tarifaJornada ? $tarifaJornada->id_tarifa : 1,
                'jornadas_consideradas' => 2.00,
                'produccion_considerada' => 0.00,
                'valor_calculado' => 100000.00,
                'fecha_generacion' => '2026-05-11',
                'fecha_liquidacion' => '2026-05-11',
                'observacion' => 'Liquidada y pagada en efectivo.',
            ]
        );

        // 5. Karen Yulieth Pascuas Hernandez - Jornada - 2026-05-11 a 2026-05-11 - 1 Jornada - $50,000 - Liquidada
        Liquidacion::firstOrCreate(
            [
                'id_trabajador' => $karen ? $karen->id_trabajador : ($fallbackWorker->id_trabajador ?? 1),
                'periodo_inicio' => '2026-05-11',
                'periodo_fin' => '2026-05-11',
            ],
            [
                'id_tarifa' => $tarifaJornada ? $tarifaJornada->id_tarifa : 1,
                'jornadas_consideradas' => 1.00,
                'produccion_considerada' => 0.00,
                'valor_calculado' => 50000.00,
                'fecha_generacion' => '2026-05-11',
                'fecha_liquidacion' => '2026-05-11',
                'estado' => 'LIQUIDADA',
                'observacion' => 'Pago realizado satisfactoriamente.',
            ]
        );
    }
}
