<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    // GET /api/v1/reservas
    public function index(Request $request)
    {
        $query = Reserva::with(['usuario', 'libro']);

        if ($request->has('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['status' => 'success', 'data' => $reservas]);
    }

    // GET /api/v1/reservas/{id}
    public function show($id)
    {
        $reserva = Reserva::with(['usuario', 'libro'])->find($id);

        if (!$reserva) {
            return response()->json(['status' => 'error', 'message' => 'Reserva no encontrada'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $reserva]);
    }

    // POST /api/v1/reservas
    public function store(Request $request)
{
    $usuarioAutenticado = $request->user();

    // Si es lector, forzar su propio ID
    $usuarioId = $usuarioAutenticado->rol === 'administrador'
        ? $request->usuario_id
        : $usuarioAutenticado->id_usuario;

    $request->validate([
        'libro_id' => 'required|exists:libros,id_libro',
    ]);

    if ($usuarioAutenticado->rol === 'administrador') {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id_usuario',
        ]);
    }

    $reservaExistente = Reserva::where('id_usuario', $usuarioId)
        ->where('id_libro', $request->libro_id)
        ->where('estado', 'pendiente')
        ->first();

    if ($reservaExistente) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Ya tienes una reserva activa para este libro'
        ], 422);
    }

    $reserva = Reserva::create([
        'id_usuario'       => $usuarioId,
        'id_libro'         => $request->libro_id,
        'fecha_reserva'    => now(),
        'fecha_expiracion' => now()->addDays(7),
        'estado'           => 'pendiente',
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Reserva creada. Tienes 7 días para retirar el libro.',
        'data'    => $reserva->load(['usuario', 'libro'])
    ], 201);
}

    // PUT /api/v1/reservas/{id}/cancelar
    public function cancelar($id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['status' => 'error', 'message' => 'Reserva no encontrada'], 404);
        }

        if ($reserva->estado !== 'pendiente') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Solo se pueden cancelar reservas pendientes'
            ], 422);
        }

        $reserva->update(['estado' => 'cancelada']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Reserva cancelada exitosamente',
            'data'    => $reserva->load(['usuario', 'libro'])
        ]);
    }

    // PUT /api/v1/reservas/{id}/completar
    public function completar($id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['status' => 'error', 'message' => 'Reserva no encontrada'], 404);
        }

        if ($reserva->estado !== 'pendiente') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Solo se pueden completar reservas pendientes'
            ], 422);
        }

        $reserva->update(['estado' => 'confirmada']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Reserva confirmada exitosamente',
            'data'    => $reserva
        ]);
    }
}