<?php

namespace App\Http\Controllers\Api\V1\Admin;

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
     * Listar TODAS las publicaciones (admin ve todo).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Publicacion::with('user');

        // Búsqueda por comentarios o usuario
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('comentarios', 'like', "%{$buscar}%")
                  ->orWhereHas('user', function ($userQuery) use ($buscar) {
                      $userQuery->where('nombre', 'like', "%{$buscar}%");
                  });
            });
        }

        // Filtrar por fecha
        if ($request->has('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $publicaciones = $query->latest()->paginate(15);

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
     * Admin crea publicación.
     */
    public function store(StorePublicacionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $publicacion = Publicacion::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Publicación creada exitosamente.',
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
     * Admin actualiza cualquier publicación.
     */
    public function update(UpdatePublicacionRequest $request, Publicacion $publicacion): JsonResponse
    {
        $publicacion->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Publicación actualizada exitosamente.',
            'data'    => new PublicacionResource($publicacion->fresh()),
        ]);
    }

    /**
     * Admin elimina cualquier publicación.
     */
    public function destroy(Publicacion $publicacion): JsonResponse
    {
        $publicacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publicación eliminada exitosamente.',
        ]);
    }
}
