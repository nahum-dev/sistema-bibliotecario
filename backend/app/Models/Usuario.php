<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Tabla personalizada (no "usuarios" por defecto ya lo deduce, pero lo declaramos explícitamente)
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombres',
        'apellidos',
        'carnet_u_identificacion',
        'correo_electronico',
        'password_hash',
        'rol',
        'activo',
    ];

    // Campos ocultos (nunca se envían en respuestas JSON)
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Tipos de datos
    protected $casts = [
        'activo' => 'boolean',
    ];

    // Laravel usa "password" por defecto para auth, aquí lo mapeamos
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // ─── RELACIONES ───────────────────────────────────────

    // Un usuario puede tener muchos préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_usuario', 'id_usuario');
    }

    // Un usuario puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_usuario', 'id_usuario');
    }

    // ─── SCOPES (filtros reutilizables) ──────────────────

    // Scope para obtener solo usuarios activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para obtener solo lectores
    public function scopeLectores($query)
    {
        return $query->where('rol', 'lector');
    }
}