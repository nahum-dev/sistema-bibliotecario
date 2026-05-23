<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autores';
    protected $primaryKey = 'id_autor';
    public $incrementing = true;

    protected $fillable = ['nombre_autor', 'nacionalidad'];
        // Un autor puede tener muchos libros (relación N:N)
        public function libros()
        {
            return $this->belongsToMany(
                Libro::class,
                'libro_autor',     // tabla pivote
                'id_autor',        // FK de este modelo en la pivote
                'id_libro',        // FK del otro modelo en la pivote
                'id_autor',        // PK de este modelo
                'id_libro'         // PK del otro modelo
            );
    }
}