<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('usuarios', function (Blueprint $table) { $table->id('id_usuario'); $table->string('nombres'); $table->string('apellidos'); $table->string('correo_electronico')->unique(); $table->string('password_hash'); $table->enum('rol', ['administrador', 'usuario'])->default('usuario'); $table->string('camel_u_identificacion')->nullable(); $table->enum('activo', ['activo', 'inactivo'])->default('activo'); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('usuarios'); }
};
