<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registro público — crea un usuario con rol alumno.
     * Reemplaza: RegistrarUsuario.php
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->only([
            'nombre', 'codigo', 'email', 'telefono',
            'nivel', 'grado', 'seccion',
        ]);

        $data['password'] = Hash::make($request->password);

        // Asignar rol de alumno por defecto
        $rolAlumno = Role::where('slug', 'alumno')->first();
        $data['role_id'] = $rolAlumno->id;

        // Subir foto de perfil si se envía
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $user = User::create($data);
        $user->load('role');

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente.',
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Iniciar sesión — genera un token Sanctum.
     * Reemplaza: IniciarSesion.php (login por UNION de 3 tablas)
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::with('role')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        // Revocar tokens anteriores (un solo dispositivo a la vez)
        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada correctamente.',
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
        ]);
    }

    /**
     * Cerrar sesión — revoca el token actual.
     * Reemplaza: loginOut.php
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    /**
     * Obtener datos del usuario autenticado.
     * Reemplaza: sesion_check.php
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()->load('role')),
        ]);
    }
}
