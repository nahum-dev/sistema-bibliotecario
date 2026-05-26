<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Categoria;
use App\Models\Autor;
use App\Models\Libro;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create(['nombres' => 'Juan', 'apellidos' => 'Pérez', 'correo_electronico' => 'juan@example.com', 'password_hash' => bcrypt('password'), 'rol' => 'administrador', 'camel_u_identificacion' => '12345678', 'activo' => 'activo']);
        Usuario::create(['nombres' => 'María', 'apellidos' => 'García', 'correo_electronico' => 'maria@example.com', 'password_hash' => bcrypt('password'), 'rol' => 'usuario', 'camel_u_identificacion' => '87654321', 'activo' => 'activo']);

        $ficcion = Categoria::create(['nombre_categoria' => 'Ficción', 'descripcion' => 'Libros de narrativa e imaginación']);
        $educacion = Categoria::create(['nombre_categoria' => 'Educación', 'descripcion' => 'Libros educativos y de aprendizaje']);
        $historia = Categoria::create(['nombre_categoria' => 'Historia', 'descripcion' => 'Libros sobre eventos históricos']);

        $cervantes = Autor::create(['nombre_autor' => 'Miguel de Cervantes', 'nacionalidad' => 'España']);
        $tolkien = Autor::create(['nombre_autor' => 'J.R.R. Tolkien', 'nacionalidad' => 'Reino Unido']);
        $asimov = Autor::create(['nombre_autor' => 'Isaac Asimov', 'nacionalidad' => 'Estados Unidos']);
        $austen = Autor::create(['nombre_autor' => 'Jane Austen', 'nacionalidad' => 'Reino Unido']);

        $libro1 = Libro::create(['titulo' => 'Don Quijote de la Mancha', 'descripcion' => 'La novela más famosa de la literatura española', 'isbn' => '978-8426349064', 'anio_publicacion' => 1605, 'editorial' => 'Penguin Clásicos', 'id_categoria' => $ficcion->id_categoria, 'cantidad_total' => 5, 'cantidad_disponible' => 3, 'estado' => 'disponible']);
        $libro1->autores()->attach($cervantes);

        $libro2 = Libro::create(['titulo' => 'El Señor de los Anillos', 'descripcion' => 'Épica fantasía sobre la búsqueda del anillo único', 'isbn' => '978-8445004310', 'anio_publicacion' => 1954, 'editorial' => 'Minotauro', 'id_categoria' => $ficcion->id_categoria, 'cantidad_total' => 3, 'cantidad_disponible' => 1, 'estado' => 'prestado']);
        $libro2->autores()->attach($tolkien);

        $libro3 = Libro::create(['titulo' => 'Fundación', 'descripcion' => 'Primera novela de la serie Fundación', 'isbn' => '978-8435012850', 'anio_publicacion' => 1951, 'editorial' => 'Ediciones B', 'id_categoria' => $ficcion->id_categoria, 'cantidad_total' => 4, 'cantidad_disponible' => 2, 'estado' => 'disponible']);
        $libro3->autores()->attach($asimov);

        $libro4 = Libro::create(['titulo' => 'Orgullo y Prejuicio', 'descripcion' => 'Romance clásico sobre matrimonio y estatus social', 'isbn' => '978-8435018609', 'anio_publicacion' => 1813, 'editorial' => 'Austral', 'id_categoria' => $ficcion->id_categoria, 'cantidad_total' => 2, 'cantidad_disponible' => 2, 'estado' => 'disponible']);
        $libro4->autores()->attach($austen);

        $libro5 = Libro::create(['titulo' => 'Introducción a la Programación en Python', 'descripcion' => 'Guía completa para aprender programación usando Python', 'isbn' => '978-9788491586265', 'anio_publicacion' => 2020, 'editorial' => 'Anaya', 'id_categoria' => $educacion->id_categoria, 'cantidad_total' => 6, 'cantidad_disponible' => 4, 'estado' => 'disponible']);

        $libro6 = Libro::create(['titulo' => 'Historia de la Revolución Francesa', 'descripcion' => 'Un análisis profundo de los eventos revolucionarios', 'isbn' => '978-8491642855', 'anio_publicacion' => 2019, 'editorial' => 'Taurus', 'id_categoria' => $historia->id_categoria, 'cantidad_total' => 3, 'cantidad_disponible' => 3, 'estado' => 'disponible']);
    }
}
