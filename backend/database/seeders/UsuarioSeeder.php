<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        Usuario::create([
            'nombres' => 'Admin',
            'apellidos' => 'Biblioteca',
            'carnet_u_identificacion' => 'ADMIN001',
            'correo_electronico' => 'admin@biblioteca.edu',
            'password_hash' => Hash::make('password123'),
            'rol' => 'administrador',
            'activo' => true,
        ]);

        // Estudiantes de prueba
        Usuario::create([
            'nombres' => 'Anderson',
            'apellidos' => 'Portillo',
            'carnet_u_identificacion' => 'PA250105',
            'correo_electronico' => 'anderson@universidad.edu',
            'password_hash' => Hash::make('password123'),
            'rol' => 'lector',
            'activo' => true,
        ]);

        Usuario::create([
            'nombres' => 'Ana Ruth',
            'apellidos' => 'López Lima',
            'carnet_u_identificacion' => 'LL250088',
            'correo_electronico' => 'ana@universidad.edu',
            'password_hash' => Hash::make('password123'),
            'rol' => 'lector',
            'activo' => true,
        ]);
    }
}