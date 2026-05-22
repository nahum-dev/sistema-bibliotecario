<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre_categoria',
        'descripcion',
    ];

    // Una categoría tiene muchos libros
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_categoria', 'id_categoria');
    }
}