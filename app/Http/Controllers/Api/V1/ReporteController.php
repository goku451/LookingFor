<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReporteRequest;
use App\Http\Requests\UpdateReporteRequest;
use App\Http\Resources\ReporteResource;
use App\Models\Reporte;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    /**
     * Listar reportes del usuario autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $reportes = $request->user()
            ->reportes()
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => ReporteResource::collection($reportes),
            'meta'    => [
                'current_page' => $reportes->currentPage(),
                'last_page'    => $reportes->lastPage(),
                'per_page'     => $reportes->perPage(),
                'total'        => $reportes->total(),
            ],
        ]);
    }

    /**
     * Crear reporte de objeto encontrado.
     * Reemplaza: RegistrarObjetoEncontrado.php
     */
    public function store(StoreReporteRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')
                ->store('reportes', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $reporte = Reporte::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Reporte registrado exitosamente.',
            'data'    => new ReporteResource($reporte),
        ], 201);
    }

    /**
     * Ver un reporte específico.
     */
    public function show(Reporte $reporte): JsonResponse
    {
        if ($reporte->user_id !== request()->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => new ReporteResource($reporte->load('user')),
        ]);
    }

    /**
     * Actualizar reporte.
     */
    public function update(UpdateReporteRequest $request, Reporte $reporte): JsonResponse
    {
        if ($reporte->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($reporte->imagen) {
                Storage::disk('public')->delete($reporte->imagen);
            }
            $data['imagen'] = $request->file('imagen')
                ->store('reportes', 'public');
        }

        $reporte->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Reporte actualizado exitosamente.',
            'data'    => new ReporteResource($reporte->fresh()),
        ]);
    }

    /**
     * Eliminar reporte (soft delete).
     */
    public function destroy(Reporte $reporte): JsonResponse
    {
        if ($reporte->user_id !== request()->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        if ($reporte->imagen) {
            Storage::disk('public')->delete($reporte->imagen);
        }

        $reporte->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reporte eliminado exitosamente.',
        ]);
    }
}
