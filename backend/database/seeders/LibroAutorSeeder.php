<?php

namespace Database\Seeders;

use App\Models\Libro;
use Illuminate\Database\Seeder;

class LibroAutorSeeder extends Seeder
{
    public function run(): void
    {
        // Clean Code → Robert C. Martin
        $libroCleanCode = Libro::find(1);
        $libroCleanCode->autores()->attach(1);

        // Introduction to Algorithms → Thomas H. Cormen
        $libroAlgorithms = Libro::find(2);
        $libroAlgorithms->autores()->attach(6);

        // Cien Años de Soledad → García Márquez
        $libroCienAnios = Libro::find(3);
        $libroCienAnios->autores()->attach(4);

        // Design Patterns → Gang of Four
        $libroPatterns = Libro::find(4);
        $libroPatterns->autores()->attach(2);
    }
}