<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/register
     * Registrar un nuevo usuario (lector)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombres'                 => 'required|string|max:100',
            'apellidos'               => 'required|string|max:100',
            'carnet_u_identificacion' => 'required|string|max:20|unique:usuarios,carnet_u_identificacion',
            'correo_electronico'      => 'required|email|max:150|unique:usuarios,correo_electronico',
            'password'                => 'required|string|min:8|confirmed',
        ], [
            'nombres.required'                        => 'El nombre es obligatorio.',
            'apellidos.required'                      => 'Los apellidos son obligatorios.',
            'carnet_u_identificacion.required'        => 'El carnet es obligatorio.',
            'carnet_u_identificacion.unique'          => 'Este carnet ya está registrado.',
            'correo_electronico.required'             => 'El correo es obligatorio.',
            'correo_electronico.email'                => 'El correo no tiene un formato válido.',
            'correo_electronico.unique'               => 'Este correo ya está registrado.',
            'password.required'                       => 'La contraseña es obligatoria.',
            'password.min'                            => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'                      => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Usuario::create([
            'nombres'                 => $validated['nombres'],
            'apellidos'               => $validated['apellidos'],
            'carnet_u_identificacion' => $validated['carnet_u_identificacion'],
            'correo_electronico'      => $validated['correo_electronico'],
            'password_hash'           => Hash::make($validated['password']),
            'rol'                     => 'lector',
            'activo'                  => true,
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente.',
            'data'    => [
                'usuario' => [
                    'id_usuario'              => $usuario->id_usuario,
                    'nombres'                 => $usuario->nombres,
                    'apellidos'               => $usuario->apellidos,
                    'correo_electronico'      => $usuario->correo_electronico,
                    'carnet_u_identificacion' => $usuario->carnet_u_identificacion,
                    'rol'                     => $usuario->rol,
                ],
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * POST /api/v1/auth/login
     * Iniciar sesión
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'password'           => 'required|string',
        ], [
            'correo_electronico.required' => 'El correo es obligatorio.',
            'correo_electronico.email'    => 'El correo no tiene un formato válido.',
            'password.required'           => 'La contraseña es obligatoria.',
        ]);

        // Buscar usuario por correo
        $usuario = Usuario::where('correo_electronico', $request->correo_electronico)->first();

        // Verificar que existe y que la contraseña es correcta
        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            throw ValidationException::withMessages([
                'correo_electronico' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar que el usuario está activo
        if (!$usuario->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ], 403);
        }

        // Eliminar tokens anteriores (solo una sesión activa)
        $usuario->tokens()->delete();

        // Crear nuevo token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada exitosamente.',
            'data'    => [
                'usuario' => [
                    'id_usuario'              => $usuario->id_usuario,
                    'nombres'                 => $usuario->nombres,
                    'apellidos'               => $usuario->apellidos,
                    'correo_electronico'      => $usuario->correo_electronico,
                    'carnet_u_identificacion' => $usuario->carnet_u_identificacion,
                    'rol'                     => $usuario->rol,
                ],
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * POST /api/v1/auth/logout
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.',
        ], 200);
    }

    /**
     * GET /api/v1/auth/me
     * Obtener datos del usuario autenticado
     */
    public function me(Request $request)
    {
        $usuario = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'usuario' => [
                    'id_usuario'              => $usuario->id_usuario,
                    'nombres'                 => $usuario->nombres,
                    'apellidos'               => $usuario->apellidos,
                    'correo_electronico'      => $usuario->correo_electronico,
                    'carnet_u_identificacion' => $usuario->carnet_u_identificacion,
                    'rol'                     => $usuario->rol,
                    'activo'                  => $usuario->activo,
                ],
            ],
        ], 200);
    }
}