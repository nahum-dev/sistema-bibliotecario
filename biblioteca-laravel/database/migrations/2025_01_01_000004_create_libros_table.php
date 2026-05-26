<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('libros', function (Blueprint $table) { $table->id('id_libro'); $table->string('titulo'); $table->text('descripcion')->nullable(); $table->string('isbn')->unique(); $table->integer('anio_publicacion')->nullable(); $table->string('editorial')->nullable(); $table->unsignedBigInteger('id_categoria'); $table->integer('cantidad_total')->default(1); $table->integer('cantidad_disponible')->default(1); $table->enum('estado', ['disponible', 'prestado', 'dañado', 'perdido'])->default('disponible'); $table->date('fecha_entrega_real')->nullable(); $table->date('fecha_devolución_prevista')->nullable(); $table->timestamps(); $table->foreign('id_categoria')->references('id_categoria')->on('categorias')->onDelete('restrict')->onUpdate('cascade'); }); }
    public function down(): void { Schema::dropIfExists('libros'); }
};
