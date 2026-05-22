<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // El orden importa por las foreign keys
        $this->call([
            CategoriaSeeder::class,
            AutorSeeder::class,
            UsuarioSeeder::class,
            LibroSeeder::class,
            LibroAutorSeeder::class,
        ]);
    }
}