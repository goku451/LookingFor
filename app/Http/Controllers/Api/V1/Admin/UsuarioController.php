<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios (con filtro por rol).
     * Reemplaza: las vistas de listado en Sistema_admin
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('role');

        // Filtrar por rol si se especifica
        if ($request->has('rol')) {
            $query->whereRelation('role', 'slug', $request->rol);
        }

        // Búsqueda por nombre o código
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        $usuarios = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => UserResource::collection($usuarios),
            'meta'    => [
                'current_page' => $usuarios->currentPage(),
                'last_page'    => $usuarios->lastPage(),
                'per_page'     => $usuarios->perPage(),
                'total'        => $usuarios->total(),
            ],
        ]);
    }

    /**
     * Crear un usuario (admin puede crear alumnos, profesores o admins).
     * Reemplaza: AgregarUsuarioAdmin.php, AgregarProfesor.php, AgregarAdmin.php
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre'   => 'required|string|max:200',
            'codigo'   => 'nullable|string|max:20',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|max:50',
            'telefono' => 'nullable|string|max:15',
            'nivel'    => 'nullable|string|max:100',
            'grado'    => 'nullable|string|max:100',
            'seccion'  => 'nullable|string|max:10',
            'role_id'  => 'required|exists:roles,id',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'role_id.exists' => 'El rol seleccionado no existe.',
        ]);

        $data = $request->only([
            'nombre', 'codigo', 'email', 'telefono',
            'nivel', 'grado', 'seccion', 'role_id',
        ]);

        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $usuario = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente.',
            'data'    => new UserResource($usuario->load('role')),
        ], 201);
    }

    /**
     * Ver un usuario específico.
     */
    public function show(User $usuario): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($usuario->load('role')),
        ]);
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, User $usuario): JsonResponse
    {
        $request->validate([
            'nombre'   => 'sometimes|required|string|max:200',
            'codigo'   => 'nullable|string|max:20',
            'email'    => 'sometimes|required|email|max:100|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6|max:50',
            'telefono' => 'nullable|string|max:15',
            'nivel'    => 'nullable|string|max:100',
            'grado'    => 'nullable|string|max:100',
            'seccion'  => 'nullable|string|max:10',
            'role_id'  => 'sometimes|exists:roles,id',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->only([
            'nombre', 'codigo', 'email', 'telefono',
            'nivel', 'grado', 'seccion', 'role_id',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($usuario->foto) {
                Storage::disk('public')->delete($usuario->foto);
            }
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $usuario->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente.',
            'data'    => new UserResource($usuario->fresh()->load('role')),
        ]);
    }

    /**
     * Eliminar usuario (soft delete).
     * Reemplaza: EliminarUsuarioAdmin.php, EliminarProfesorAdmin.php, EliminarAdmin.php
     */
    public function destroy(User $usuario): JsonResponse
    {
        if ($usuario->foto) {
            Storage::disk('public')->delete($usuario->foto);
        }

        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente.',
        ]);
    }
}
