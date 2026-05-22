<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $primaryKey = 'id_libro';

    protected $fillable = [
        'id_categoria',
        'titulo',
        'isbn',
        'editorial',
        'anio_publicacion',
        'cantidad_total',
        'cantidad_disponible',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'cantidad_total' => 'integer',
        'cantidad_disponible' => 'integer',
    ];

    // ─── RELACIONES ───────────────────────────────────────

    // Un libro pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Un libro puede tener muchos autores (N:N)
    public function autores()
    {
        return $this->belongsToMany(
            Autor::class,
            'libro_autor',
            'id_libro',
            'id_autor',
            'id_libro',
            'id_autor'
        );
    }

    // Un libro puede tener muchos préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_libro', 'id_libro');
    }

    // Un libro puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_libro', 'id_libro');
    }

    // ─── SCOPES ──────────────────────────────────────────

    // Solo libros disponibles
    public function scopeDisponibles($query)
    {
        return $query->where('cantidad_disponible', '>', 0)->where('activo', true);
    }
}