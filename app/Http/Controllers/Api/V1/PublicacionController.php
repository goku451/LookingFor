<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicacionRequest;
use App\Http\Requests\UpdatePublicacionRequest;
use App\Http\Resources\PublicacionResource;
use App\Models\Publicacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicacionController extends Controller
{
    /**
     * Listar publicaciones del usuario autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $publicaciones = $request->user()
            ->publicaciones()
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => PublicacionResource::collection($publicaciones),
            'meta'    => [
                'current_page' => $publicaciones->currentPage(),
                'last_page'    => $publicaciones->lastPage(),
                'per_page'     => $publicaciones->perPage(),
                'total'        => $publicaciones->total(),
            ],
        ]);
    }

    /**
     * Crear publicación / comentario.
     * Reemplaza: RegistrarPublicacion.php
     */
    public function store(StorePublicacionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $publicacion = Publicacion::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Publicación registrada exitosamente.',
            'data'    => new PublicacionResource($publicacion),
        ], 201);
    }

    /**
     * Ver una publicación específica.
     */
    public function show(Publicacion $publicacion): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new PublicacionResource($publicacion->load('user')),
        ]);
    }

    /**
     * Actualizar publicación.
     */
    public function update(UpdatePublicacionRequest $request, Publicacion $publicacion): JsonResponse
    {
        // Solo el dueño puede editar su publicación
        if ($publicacion->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar esta publicación.',
            ], 403);
        }

        $publicacion->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Publicación actualizada exitosamente.',
            'data'    => new PublicacionResource($publicacion->fresh()),
        ]);
    }

    /**
     * Eliminar publicación.
     */
    public function destroy(Request $request, Publicacion $publicacion): JsonResponse
    {
        // Solo el dueño puede eliminar su publicación
        if ($publicacion->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar esta publicación.',
            ], 403);
        }

        $publicacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publicación eliminada exitosamente.',
        ]);
    }
}
