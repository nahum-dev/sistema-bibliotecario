<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'id_reserva';

    protected $fillable = [
        'id_usuario',
        'id_libro',
        'fecha_reserva',
        'fecha_expiracion',
        'estado',
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'fecha_expiracion' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro', 'id_libro');
    }
}