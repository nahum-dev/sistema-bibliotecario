<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('libro_autor', function (Blueprint $table) { $table->id(); $table->unsignedBigInteger('id_libro'); $table->unsignedBigInteger('id_autor'); $table->timestamps(); $table->foreign('id_libro')->references('id_libro')->on('libros')->onDelete('cascade')->onUpdate('cascade'); $table->foreign('id_autor')->references('id_autor')->on('autores')->onDelete('cascade')->onUpdate('cascade'); $table->unique(['id_libro', 'id_autor']); }); }
    public function down(): void { Schema::dropIfExists('libro_autor'); }
};
