<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'id_prestamo';
    public $timestamps = true;
    protected $fillable = ['id_usuario', 'id_libro', 'fecha_salida', 'fecha_devolución_prevista', 'fecha_entrega_real', 'estado'];
    protected $casts = ['fecha_salida' => 'date', 'fecha_devolución_prevista' => 'date', 'fecha_entrega_real' => 'date'];
    public function usuario(): BelongsTo { return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario'); }
    public function libro(): BelongsTo { return $this->belongsTo(Libro::class, 'id_libro', 'id_libro'); }
}
