<?php

namespace Database\Seeders;

use App\Models\Liquidacion;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('rol', 'ADMINISTRADOR')->first() ?? User::first();
        $adminId = $admin ? $admin->id_usuario : 1;

        // Buscar liquidación de Paola Garcia
        $liqPaola = Liquidacion::whereHas('trabajador.usuario', function ($q) {
            $q->where('nombres', 'like', '%paola%');
        })->first() ?? Liquidacion::first();

        if ($liqPaola) {
            Pago::firstOrCreate(
                [
                    'id_liquidacion' => $liqPaola->id_liquidacion,
                    'fecha_pago' => '2026-05-12',
                ],
                [
                    'id_usuario_registrador' => $adminId,
                    'monto' => 100000.00,
                    'metodo_pago' => 'Efectivo',
                    'referencia_pago' => null,
                    'observacion' => 'Pago de liquidación por 2 jornadas en efectivo.',
                ]
            );
        }
    }
}
