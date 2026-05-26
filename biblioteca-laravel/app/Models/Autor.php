<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    protected $table = 'autores';
    protected $primaryKey = 'id_autor';
    public $timestamps = true;

    protected $fillable = [
        'nombre_autor',
        'nacionalidad',
    ];

    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(
            Libro::class,
            'libro_autor',
            'id_autor',
            'id_libro',
            'id_autor',
            'id_libro'
        );
    }
}