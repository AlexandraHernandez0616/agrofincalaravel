<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('usuarios')->updateOrInsert(
            ['username' => 'admin'], // Condiciones de búsqueda
            [
                'nombres' => 'Admin',
                'apellidos' => 'Agrofinca',
                'documento' => '123456789',
                'telefono' => '3000000000',
                'password_hash' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'rol' => 'ADMINISTRADOR',
                'activo' => true,
                'fecha_creacion' => now(),
            ]
        );
    }
}
