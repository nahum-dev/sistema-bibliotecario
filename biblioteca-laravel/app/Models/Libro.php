<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Libro extends Model
{
    protected $table = 'libros';
    protected $primaryKey = 'id_libro';
    public $timestamps = true;
    protected $fillable = ['titulo', 'descripcion', 'isbn', 'anio_publicacion', 'editorial', 'id_categoria', 'cantidad_total', 'cantidad_disponible', 'estado', 'fecha_entrega_real', 'fecha_devolución_prevista'];
    protected $casts = ['fecha_entrega_real' => 'date', 'fecha_devolución_prevista' => 'date'];
    public function categoria(): BelongsTo { return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria'); }
    public function prestamos(): HasMany { return $this->hasMany(Prestamo::class, 'id_libro', 'id_libro'); }
    public function autores(): BelongsToMany { return $this->belongsToMany(Autor::class, 'libro_autor', 'id_libro', 'id_autor', 'id_libro', 'id_autor'); }
}
