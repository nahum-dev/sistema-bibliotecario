<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->foreignId('id_usuario')
                  ->constrained('usuarios', 'id_usuario')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('id_libro')
                  ->constrained('libros', 'id_libro')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->date('fecha_reserva');
            $table->date('fecha_expiracion');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'expirada'])
                  ->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};