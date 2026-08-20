<?php

namespace Database\Seeders;

use App\Models\AutorizacionDelegada;
use App\Models\Liquidacion;
use App\Models\User;
use Illuminate\Database\Seeder;

class AutorizacionDelegadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('rol', 'ADMINISTRADOR')->first() ?? User::first();
        $adminId = $admin ? $admin->id_usuario : 1;

        $jeanPierre = User::where('rol', 'MAYORDOMO')
            ->where('nombres', 'like', '%jean%')
            ->first() ?? User::where('rol', 'MAYORDOMO')->first();

        $mayordomoId = $jeanPierre ? $jeanPierre->id_usuario : 4;

        // 1. Jean Pierre Arias - 2026-05-14 a 2026-05-14 - Vencido - 1 Liquidación
        $aut1 = AutorizacionDelegada::firstOrCreate(
            [
                'id_mayordomo' => $mayordomoId,
                'fecha_inicio' => '2026-05-14',
                'fecha_fin' => '2026-05-14',
            ],
            [
                'id_administrador' => $adminId,
                'acciones_permitidas' => 'Liquidaciones de Pago y Cosecha',
                'monto_maximo' => 500000.00,
                'estado' => 'VENCIDA',
            ]
        );

        // 2. Jean Pierre Arias - 2026-05-12 a 2026-05-13 - Revocado - 1 Liquidación
        $aut2 = AutorizacionDelegada::firstOrCreate(
            [
                'id_mayordomo' => $mayordomoId,
                'fecha_inicio' => '2026-05-12',
                'fecha_fin' => '2026-05-13',
            ],
            [
                'id_administrador' => $adminId,
                'acciones_permitidas' => 'Liquidaciones de Pago',
                'monto_maximo' => 300000.00,
                'estado' => 'REVOCADA',
            ]
        );

        // Asignar id_autorizacion a liquidaciones de prueba para que el conteo refleje 1
        $liq1 = Liquidacion::first();
        if ($liq1 && empty($liq1->id_autorizacion)) {
            $liq1->update(['id_autorizacion' => $aut1->id_autorizacion]);
        }

        $liq2 = Liquidacion::skip(1)->first();
        if ($liq2 && empty($liq2->id_autorizacion)) {
            $liq2->update(['id_autorizacion' => $aut2->id_autorizacion]);
        }
    }
}
