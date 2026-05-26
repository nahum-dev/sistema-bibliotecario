<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('prestamos', function (Blueprint $table) { $table->id('id_prestamo'); $table->unsignedBigInteger('id_usuario'); $table->unsignedBigInteger('id_libro'); $table->date('fecha_salida'); $table->date('fecha_devolución_prevista'); $table->date('fecha_entrega_real')->nullable(); $table->enum('estado', ['activo', 'devuelto', 'vencido'])->default('activo'); $table->timestamps(); $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('restrict')->onUpdate('cascade'); $table->foreign('id_libro')->references('id_libro')->on('libros')->onDelete('restrict')->onUpdate('cascade'); }); }
    public function down(): void { Schema::dropIfExists('prestamos'); }
};
