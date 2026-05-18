<?php

namespace App\Http\Controllers\Api\V1\Admin;

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
     * Listar TODOS los objetos (admin ve todo).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Objeto::with('user');

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('lugar', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        $objetos = $query->latest()->paginate(15);

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
     * Admin crea objeto.
     * Reemplaza: AgregarObjetoAdmin.php
     */
    public function store(StoreObjetoRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('objetos', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $objeto = Objeto::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Objeto creado exitosamente.',
            'data'    => new ObjetoResource($objeto),
        ], 201);
    }

    public function show(Objeto $objeto): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new ObjetoResource($objeto->load('user')),
        ]);
    }

    /**
     * Admin edita objeto.
     * Reemplaza: EditarObjetoAdmin.php
     */
    public function update(UpdateObjetoRequest $request, Objeto $objeto): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($objeto->imagen) {
                Storage::disk('public')->delete($objeto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('objetos', 'public');
        }

        $objeto->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Objeto actualizado exitosamente.',
            'data'    => new ObjetoResource($objeto->fresh()),
        ]);
    }

    /**
     * Admin elimina objeto.
     * Reemplaza: EliminarObjetoAdmin.php
     */
    public function destroy(Objeto $objeto): JsonResponse
    {
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
