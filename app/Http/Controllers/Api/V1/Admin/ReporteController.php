<?php

namespace App\Http\Controllers\Api\V1\Admin;

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
     * Listar TODOS los reportes (admin ve todo).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reporte::with('user');

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_reportante', 'like', "%{$buscar}%")
                  ->orWhere('nombre_objeto', 'like', "%{$buscar}%")
                  ->orWhere('lugar', 'like', "%{$buscar}%");
            });
        }

        $reportes = $query->latest()->paginate(15);

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

    public function store(StoreReporteRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('reportes', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $reporte = Reporte::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Reporte creado exitosamente.',
            'data'    => new ReporteResource($reporte),
        ], 201);
    }

    public function show(Reporte $reporte): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new ReporteResource($reporte->load('user')),
        ]);
    }

    /**
     * Reemplaza: EditarReporteAdmin.php
     */
    public function update(UpdateReporteRequest $request, Reporte $reporte): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($reporte->imagen) {
                Storage::disk('public')->delete($reporte->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('reportes', 'public');
        }

        $reporte->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Reporte actualizado exitosamente.',
            'data'    => new ReporteResource($reporte->fresh()),
        ]);
    }

    /**
     * Reemplaza: EliminarReporteAdmin.php
     */
    public function destroy(Reporte $reporte): JsonResponse
    {
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
