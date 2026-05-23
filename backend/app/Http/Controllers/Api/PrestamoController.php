<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    // GET /api/v1/prestamos
    public function index(Request $request)
    {
        $query = Prestamo::with(['usuario', 'libro']);

        if ($request->has('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $prestamos = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['status' => 'success', 'data' => $prestamos]);
    }

    // GET /api/v1/prestamos/{id}
    public function show($id)
    {
        $prestamo = Prestamo::with(['usuario', 'libro'])->find($id);

        if (!$prestamo) {
            return response()->json(['status' => 'error', 'message' => 'Préstamo no encontrado'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $prestamo]);
    }

    // POST /api/v1/prestamos
    public function store(Request $request)
    {
        $validated = $request->validate([
    'usuario_id'                => 'required|exists:usuarios,id_usuario',
    'libro_id'                  => 'required|exists:libros,id_libro',
    'fecha_devolucion_esperada' => 'required|date|after:today',
]);

        // Control de stock atómico
        $resultado = DB::transaction(function () use ($validated) {
            $libro = Libro::lockForUpdate()->find($validated['libro_id']);

            if ($libro->cantidad_disponible < 1) {
                return ['error' => 'No hay ejemplares disponibles de este libro'];
            }

            $libro->decrement('cantidad_disponible');

            $prestamo = Prestamo::create([
    'id_usuario'               => $validated['usuario_id'],
    'id_libro'                 => $validated['libro_id'],
    'fecha_salida'             => now(),
    'fecha_devolucion_prevista'=> $validated['fecha_devolucion_esperada'],
    'estado'                   => 'activo',
]);
            return ['prestamo' => $prestamo->load(['usuario', 'libro'])];
        });

        if (isset($resultado['error'])) {
            return response()->json(['status' => 'error', 'message' => $resultado['error']], 422);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Préstamo creado exitosamente',
            'data'    => $resultado['prestamo']
        ], 201);
    }

    // PUT /api/v1/prestamos/{id}/devolver
    public function devolver($id)
    {
        $prestamo = Prestamo::find($id);

        if (!$prestamo) {
            return response()->json(['status' => 'error', 'message' => 'Préstamo no encontrado'], 404);
        }

        if ($prestamo->estado !== 'activo') {
            return response()->json(['status' => 'error', 'message' => 'Este préstamo ya fue devuelto o está vencido'], 422);
        }

        DB::transaction(function () use ($prestamo) {
           $prestamo->update([
    'fecha_entrega_real' => now(),
    'estado'             => 'devuelto',
]);

            Libro::find($prestamo->id_libro)->increment('cantidad_disponible');
        });

        return response()->json([
    'status'  => 'success',
    'message' => 'Devolución registrada exitosamente',
    'data'    => $prestamo->load(['usuario', 'libro'])
]);
    }

    // GET /api/v1/prestamos/vencidos
    public function vencidos()
{
    $vencidos = Prestamo::with(['usuario', 'libro'])
        ->where('estado', 'activo')
        ->where('fecha_devolucion_prevista', '<', now())
        ->get()
        ->map(function ($prestamo) {
            $prestamo->dias_vencido = now()->diffInDays($prestamo->fecha_devolucion_prevista);
            return $prestamo;
        });

    return response()->json(['status' => 'success', 'data' => $vencidos]);
}
}