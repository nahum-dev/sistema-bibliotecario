<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    // GET /api/v1/usuarios
    public function index()
{
    $usuarios = Usuario::select('id_usuario','nombres','apellidos','correo_electronico','rol','activo','created_at')
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json(['status' => 'success', 'data' => $usuarios]);
}

public function show($id)
{
    $usuario = Usuario::select('id_usuario','nombres','apellidos','correo_electronico','rol','activo','created_at')
        ->find($id);

    if (!$usuario) {
        return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado'], 404);
    }

    return response()->json(['status' => 'success', 'data' => $usuario]);
}

    // POST /api/v1/usuarios
public function store(Request $request)
{
    $validated = $request->validate([
        'nombres'                 => 'required|string|max:255',
        'apellidos'               => 'required|string|max:255',
        'carnet_u_identificacion' => 'required|string|max:50',
        'correo_electronico'      => 'required|email|unique:usuarios,correo_electronico',
        'password'                => 'required|string|min:8',
        'rol'                     => 'required|in:administrador,bibliotecario,estudiante,lector',
    ]);

    $usuario = Usuario::create([
        'nombres'                 => $validated['nombres'],
        'apellidos'               => $validated['apellidos'],
        'carnet_u_identificacion' => $validated['carnet_u_identificacion'],
        'correo_electronico'      => $validated['correo_electronico'],
        'password_hash'           => Hash::make($validated['password']),
        'rol'                     => $validated['rol'],
        'activo'                  => true,
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Usuario creado exitosamente',
        'data'    => $usuario->only(['id_usuario','nombres','apellidos','correo_electronico','rol','activo'])
    ], 201);
}

    // PUT /api/v1/usuarios/{id}
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre'             => 'sometimes|string|max:255',
            'correo_electronico' => ['sometimes','email', Rule::unique('usuarios','correo_electronico')->ignore($id)],
            'password'           => 'sometimes|string|min:8',
            'rol'                => 'sometimes|in:administrador,bibliotecario,estudiante',
            'activo'             => 'sometimes|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
            unset($validated['password']);
        }

        $usuario->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Usuario actualizado',
            'data'    => $usuario->only(['id','nombre','correo_electronico','rol','activo'])
        ]);
    }

    // DELETE /api/v1/usuarios/{id}
    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['status' => 'success', 'message' => 'Usuario eliminado']);
    }
}