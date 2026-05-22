<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre_categoria' => 'Ingeniería', 'descripcion' => 'Libros de ingeniería y ciencias aplicadas'],
            ['nombre_categoria' => 'Matemáticas', 'descripcion' => 'Cálculo, álgebra, estadística y más'],
            ['nombre_categoria' => 'Programación', 'descripcion' => 'Lenguajes de programación y desarrollo de software'],
            ['nombre_categoria' => 'Literatura', 'descripcion' => 'Novelas, cuentos y poesía'],
            ['nombre_categoria' => 'Historia', 'descripcion' => 'Historia universal y latinoamericana'],
            ['nombre_categoria' => 'Ciencias', 'descripcion' => 'Física, química y biología'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}