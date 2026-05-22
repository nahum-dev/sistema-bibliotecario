<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id('id_prestamo');
            $table->foreignId('id_usuario')
                  ->constrained('usuarios', 'id_usuario')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreignId('id_libro')
                  ->constrained('libros', 'id_libro')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->date('fecha_salida');
            $table->date('fecha_devolucion_prevista');
            $table->date('fecha_entrega_real')->nullable();
            $table->enum('estado', ['activo', 'devuelto', 'vencido'])->default('activo');
            $table->boolean('activo')->default(true);
            $table->decimal('multa', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};