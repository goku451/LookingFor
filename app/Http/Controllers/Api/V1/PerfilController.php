<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarPasswordRequest;
use App\Http\Requests\UpdatePerfilRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    /**
     * Obtener perfil del usuario autenticado.
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()->load('role')),
        ]);
    }

    /**
     * Actualizar perfil del usuario autenticado.
     * El usuario NO puede cambiar su rol ni su contraseña desde aquí.
     * Reemplaza: EditarPerfil.php
     */
    public function update(UpdatePerfilRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->only([
            'nombre', 'codigo', 'email', 'telefono',
            'nivel', 'grado', 'seccion',
        ]);

        // Subir nueva foto de perfil
        if ($request->hasFile('foto')) {
            // Eliminar la anterior si existe
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente.',
            'data'    => new UserResource($user->fresh()->load('role')),
        ]);
    }

    /**
     * Cambiar contraseña del usuario autenticado.
     * Reemplaza: CambiarPassword.php
     */
    public function cambiarPassword(CambiarPasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta.',
                'errors'  => [
                    'password_actual' => ['La contraseña actual es incorrecta.'],
                ],
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revocar todos los tokens (cierra sesión en otros dispositivos)
        $user->tokens()->delete();

        // Generar un nuevo token para la sesión actual
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada exitosamente.',
            'data'    => [
                'token' => $token,
            ],
        ]);
    }
}
