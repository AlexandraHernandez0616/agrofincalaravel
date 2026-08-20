<?php

namespace Database\Seeders;

use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrabajadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trabajadores = [
            [
                'nombres' => 'juanito',
                'apellidos' => 'peres',
                'documento' => '74653',
                'telefono' => '3109876541',
                'username' => 'jperes',
                'eps' => 'cafam',
                'rh' => 'a+',
                'estado_trabajador' => 'ACTIVO',
                'fecha_ingreso' => '2026-05-21',
            ],
            [
                'nombres' => 'cristian',
                'apellidos' => 'longas',
                'documento' => '17834623',
                'telefono' => '3124567890',
                'username' => 'clongas',
                'eps' => 'sura',
                'rh' => 'ab+',
                'estado_trabajador' => 'ACTIVO',
                'fecha_ingreso' => '2026-05-21',
            ],
            [
                'nombres' => 'cristian',
                'apellidos' => 'cortes',
                'documento' => '98765',
                'telefono' => '3157890123',
                'username' => 'ccortes',
                'eps' => 'cafam',
                'rh' => 'a+',
                'estado_trabajador' => 'INACTIVO',
                'fecha_ingreso' => '2026-05-13',
            ],
            [
                'nombres' => 'fani',
                'apellidos' => 'camacho',
                'documento' => '62472345',
                'telefono' => '3189012345',
                'username' => 'fcamacho',
                'eps' => 'sanitas',
                'rh' => 'a+',
                'estado_trabajador' => 'INACTIVO',
                'fecha_ingreso' => '2026-05-13',
            ],
            [
                'nombres' => 'paola',
                'apellidos' => 'garcia',
                'documento' => '123456',
                'telefono' => '3112345678',
                'username' => 'pgarcia',
                'eps' => 'capital salud',
                'rh' => 'a+',
                'estado_trabajador' => 'ACTIVO',
                'fecha_ingreso' => '2026-04-23',
            ],
            [
                'nombres' => 'Mayra',
                'apellidos' => 'pascuas',
                'documento' => '12344',
                'telefono' => '3145678901',
                'username' => 'mpascuas',
                'eps' => 'sanitas',
                'rh' => 'o+',
                'estado_trabajador' => 'ACTIVO',
                'fecha_ingreso' => '2026-04-22',
            ],
            [
                'nombres' => 'Karen Yulieth',
                'apellidos' => 'Pascuas Hernandez',
                'documento' => '1234567',
                'telefono' => '3167890123',
                'username' => 'kpascuas',
                'eps' => 'cafam',
                'rh' => 'a+',
                'estado_trabajador' => 'ACTIVO',
                'fecha_ingreso' => '2026-04-22',
            ],
        ];

        foreach ($trabajadores as $data) {
            // 1. Crear o actualizar el registro en usuarios
            $user = User::updateOrCreate(
                ['documento' => $data['documento']],
                [
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'telefono' => $data['telefono'],
                    'username' => $data['username'],
                    'password_hash' => Hash::make($data['documento']),
                    'rol' => 'TRABAJADOR',
                    'activo' => $data['estado_trabajador'] === 'ACTIVO',
                    'fecha_creacion' => $data['fecha_ingreso'] . ' 08:00:00',
                ]
            );

            // 2. Crear o actualizar el registro específico en trabajadores
            Trabajador::updateOrCreate(
                ['id_usuario' => $user->id_usuario],
                [
                    'eps' => $data['eps'],
                    'rh' => $data['rh'],
                    'estado_trabajador' => $data['estado_trabajador'],
                    'fecha_ingreso' => $data['fecha_ingreso'],
                    'hora_registro' => $data['fecha_ingreso'] . ' 08:00:00',
                ]
            );
        }
    }
}
