<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;
    protected $fillable = ['nombres', 'apellidos', 'correo_electronico', 'password_hash', 'rol', 'camel_u_identificacion', 'activo'];
    protected $hidden = ['password_hash'];
    public function prestamos(): HasMany { return $this->hasMany(Prestamo::class, 'id_usuario', 'id_usuario'); }
}
