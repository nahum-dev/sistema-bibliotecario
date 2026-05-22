<?php

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        $autores = [
            ['nombre_autor' => 'Robert C. Martin', 'nacionalidad' => 'Estadounidense'],
            ['nombre_autor' => 'Gang of Four', 'nacionalidad' => 'Internacional'],
            ['nombre_autor' => 'Donald Knuth', 'nacionalidad' => 'Estadounidense'],
            ['nombre_autor' => 'Gabriel García Márquez', 'nacionalidad' => 'Colombiana'],
            ['nombre_autor' => 'Mario Vargas Llosa', 'nacionalidad' => 'Peruana'],
            ['nombre_autor' => 'Thomas H. Cormen', 'nacionalidad' => 'Estadounidense'],
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}