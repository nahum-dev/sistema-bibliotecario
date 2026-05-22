<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'id_prestamo';

    protected $fillable = [
        'id_usuario',
        'id_libro',
        'fecha_salida',
        'fecha_devolucion_prevista',
        'fecha_entrega_real',
        'estado',
        'activo',
        'multa',
    ];

    protected $casts = [
        'fecha_salida' => 'date',
        'fecha_devolucion_prevista' => 'date',
        'fecha_entrega_real' => 'date',
        'activo' => 'boolean',
        'multa' => 'decimal:2',
    ];

    // ─── RELACIONES ───────────────────────────────────────

    // Un préstamo pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Un préstamo pertenece a un libro
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro', 'id_libro');
    }

    // ─── SCOPES ──────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'vencido');
    }
}