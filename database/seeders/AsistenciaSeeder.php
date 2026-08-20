<?php

namespace Database\Seeders;

use App\Models\Asistencia;
use App\Models\Trabajador;
use Illuminate\Database\Seeder;

class AsistenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cristian Cortes
        $tCristian = Trabajador::whereHas('usuario', function ($q) {
            $q->where('nombres', 'like', '%cristian%')->where('apellidos', 'like', '%cortes%');
        })->first() ?? Trabajador::first();

        // 2. Fani Camacho
        $tFani = Trabajador::whereHas('usuario', function ($q) {
            $q->where('nombres', 'like', '%fani%');
        })->first() ?? Trabajador::skip(1)->first();

        // 3. Mayra Pascuas
        $tMayra = Trabajador::whereHas('usuario', function ($q) {
            $q->where('nombres', 'like', '%mayra%');
        })->first() ?? Trabajador::skip(2)->first();

        if ($tCristian) {
            Asistencia::firstOrCreate(
                [
                    'id_trabajador' => $tCristian->id_trabajador,
                    'fecha' => '2026-05-21',
                ],
                [
                    'hora_entrada' => '14:13:00',
                    'hora_salida' => '14:14:00',
                ]
            );
        }

        if ($tFani) {
            Asistencia::firstOrCreate(
                [
                    'id_trabajador' => $tFani->id_trabajador,
                    'fecha' => '2026-05-13',
                ],
                [
                    'hora_entrada' => '04:03:00',
                    'hora_salida' => '04:03:00',
                ]
            );
        }

        if ($tMayra) {
            Asistencia::firstOrCreate(
                [
                    'id_trabajador' => $tMayra->id_trabajador,
                    'fecha' => '2026-05-12',
                ],
                [
                    'hora_entrada' => '02:32:00',
                    'hora_salida' => null,
                ]
            );
        }
    }
}
