<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libro_autor', function (Blueprint $table) {
            // Clave primaria compuesta
            $table->foreignId('id_libro')
                  ->constrained('libros', 'id_libro')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('id_autor')
                  ->constrained('autores', 'id_autor')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Clave primaria compuesta (un libro no puede tener al mismo autor dos veces)
            $table->primary(['id_libro', 'id_autor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libro_autor');
    }
};