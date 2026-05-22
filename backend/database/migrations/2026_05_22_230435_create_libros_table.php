<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id('id_libro');
            $table->foreignId('id_categoria')
                  ->constrained('categorias', 'id_categoria')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->string('titulo', 200);
            $table->string('isbn', 20)->unique()->nullable();
            $table->string('editorial', 150)->nullable();
            $table->year('anio_publicacion')->nullable();
            $table->integer('cantidad_total')->default(1);
            $table->integer('cantidad_disponible')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};