<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreObjetoRequest;
use App\Http\Requests\UpdateObjetoRequest;
use App\Http\Resources\ObjetoResource;
use App\Models\Objeto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObjetoController extends Controller
{
    /**
     * Listar objetos perdidos del usuario autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $objetos = $request->user()
            ->objetos()
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => ObjetoResource::collection($objetos),
            'meta'    => [
                'current_page' => $objetos->currentPage(),
                'last_page'    => $objetos->lastPage(),
                'per_page'     => $objetos->perPage(),
                'total'        => $objetos->total(),
            ],
        ]);
    }

    /**
     * Crear objeto perdido.
     * Reemplaza: RegistrarObjetoPerdido.php
     */
    public function store(StoreObjetoRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Subir imagen al storage
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')
                ->store('objetos', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $objeto = Objeto::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Objeto registrado exitosamente.',
            'data'    => new ObjetoResource($objeto),
        ], 201);
    }

    /**
     * Ver un objeto específico.
     */
    public function show(Objeto $objeto): JsonResponse
    {
        // Verificar que el objeto pertenece al usuario
        if ($objeto->user_id !== request()->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => new ObjetoResource($objeto->load('user')),
        ]);
    }

    /**
     * Actualizar objeto.
     */
    public function update(UpdateObjetoRequest $request, Objeto $objeto): JsonResponse
    {
        // Verificar que el objeto pertenece al usuario
        if ($objeto->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $data = $request->validated();

        // Reemplazar imagen si se envía una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar la anterior
            if ($objeto->imagen) {
                Storage::disk('public')->delete($objeto->imagen);
            }
            $data['imagen'] = $request->file('imagen')
                ->store('objetos', 'public');
        }

        $objeto->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Objeto actualizado exitosamente.',
            'data'    => new ObjetoResource($objeto->fresh()),
        ]);
    }

    /**
     * Eliminar objeto (soft delete).
     */
    public function destroy(Objeto $objeto): JsonResponse
    {
        // Verificar que el objeto pertenece al usuario
        if ($objeto->user_id !== request()->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        // Eliminar imagen del storage
        if ($objeto->imagen) {
            Storage::disk('public')->delete($objeto->imagen);
        }

        $objeto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Objeto eliminado exitosamente.',
        ]);
    }
}
