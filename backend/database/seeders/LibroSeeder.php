<?php

namespace Database\Seeders;

use App\Models\Libro;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $libros = [
            [
                'id_categoria' => 3, // Programación
                'titulo' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'isbn' => '978-0132350884',
                'editorial' => 'Prentice Hall',
                'anio_publicacion' => 2008,
                'cantidad_total' => 3,
                'cantidad_disponible' => 3,
            ],
            [
                'id_categoria' => 3, // Programación
                'titulo' => 'Introduction to Algorithms',
                'isbn' => '978-0262033848',
                'editorial' => 'MIT Press',
                'anio_publicacion' => 2009,
                'cantidad_total' => 2,
                'cantidad_disponible' => 2,
            ],
            [
                'id_categoria' => 4, // Literatura
                'titulo' => 'Cien Años de Soledad',
                'isbn' => '978-0307474728',
                'editorial' => 'Vintage Español',
                'anio_publicacion' => 1967,
                'cantidad_total' => 5,
                'cantidad_disponible' => 5,
            ],
            [
                'id_categoria' => 1, // Ingeniería
                'titulo' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'isbn' => '978-0201633610',
                'editorial' => 'Addison-Wesley',
                'anio_publicacion' => 1994,
                'cantidad_total' => 2,
                'cantidad_disponible' => 2,
            ],
        ];

        foreach ($libros as $libro) {
            Libro::create($libro);
        }
    }
}