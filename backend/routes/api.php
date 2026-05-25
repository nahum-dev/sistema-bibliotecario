<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\PrestamoController;
use App\Http\Controllers\Api\ReservaController;

Route::prefix('v1')->group(function () {

    // ─── RUTAS PÚBLICAS ───────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);
    });

    // ─── RUTAS PROTEGIDAS ─────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        // Categorías y Autores — solo lectura para todos
        Route::get('categorias', function () {
            return response()->json(['success' => true, 'data' => App\Models\Categoria::all()]);
        });
        Route::get('autores', function () {
            return response()->json(['success' => true, 'data' => App\Models\Autor::all()]);
        });

        // Usuarios — solo administrador
        Route::middleware('role:administrador')->group(function () {
            Route::apiResource('usuarios', UsuarioController::class);
        });

        // Libros — todos pueden ver, solo admin puede modificar
        Route::get('libros',      [LibroController::class, 'index']);
        Route::get('libros/{id}', [LibroController::class, 'show']);
        Route::middleware('role:administrador')->group(function () {
            Route::post('libros',        [LibroController::class, 'store']);
            Route::put('libros/{id}',    [LibroController::class, 'update']);
            Route::delete('libros/{id}', [LibroController::class, 'destroy']);
        });

        // Préstamos — todos pueden crear, solo admin puede ver todos y devolver
        Route::get('prestamos',     [PrestamoController::class, 'index']);
        Route::post('prestamos',    [PrestamoController::class, 'store']);
        Route::get('prestamos/{id}',[PrestamoController::class, 'show']);
        Route::middleware('role:administrador')->group(function () {
            Route::put('prestamos/{id}/devolver', [PrestamoController::class, 'devolver']);
            Route::get('prestamos-vencidos',      [PrestamoController::class, 'vencidos']);
        });

        // Reservas — lector solo puede crear y cancelar las suyas
        //            admin puede ver todas y confirmar
        Route::get('reservas',       [ReservaController::class, 'index']);
        Route::get('reservas/{id}',  [ReservaController::class, 'show']);
        Route::post('reservas',      [ReservaController::class, 'store']);
        Route::put('reservas/{id}/cancelar', [ReservaController::class, 'cancelar']);
        Route::middleware('role:administrador')->group(function () {
            Route::put('reservas/{id}/completar', [ReservaController::class, 'completar']);
        });
    });
});