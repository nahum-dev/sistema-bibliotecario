<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\PrestamoController;
use App\Http\Controllers\Api\ReservaController;

Route::prefix('v1')->group(function () {

    // ─── RUTAS PÚBLICAS ───────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);
    });

    // ─── RUTAS PROTEGIDAS ─────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        // Usuarios — solo administrador
        Route::middleware('role:administrador')->group(function () {
            Route::apiResource('usuarios', UsuarioController::class);
        });

        // Libros — todos pueden ver, admin/bibliotecario pueden modificar
        Route::get('libros',       [LibroController::class, 'index']);
        Route::get('libros/{id}',  [LibroController::class, 'show']);
        Route::middleware('role:administrador,bibliotecario')->group(function () {
            Route::post('libros',          [LibroController::class, 'store']);
            Route::put('libros/{id}',      [LibroController::class, 'update']);
            Route::delete('libros/{id}',   [LibroController::class, 'destroy']);
        });

        // Préstamos — admin y bibliotecario
        Route::middleware('role:administrador,bibliotecario')->group(function () {
            Route::apiResource('prestamos', PrestamoController::class)->except(['update', 'destroy']);
            Route::put('prestamos/{id}/devolver', [PrestamoController::class, 'devolver']);
            Route::get('prestamos-vencidos',      [PrestamoController::class, 'vencidos']);
        });

        // Reservas — todos los roles
        Route::apiResource('reservas', ReservaController::class)->only(['index', 'show', 'store']);
        Route::put('reservas/{id}/cancelar',   [ReservaController::class, 'cancelar']);
        Route::put('reservas/{id}/completar',  [ReservaController::class, 'completar']);
    });
});