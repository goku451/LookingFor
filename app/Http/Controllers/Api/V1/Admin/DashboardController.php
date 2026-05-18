<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Objeto;
use App\Models\Publicacion;
use App\Models\Reporte;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Estadísticas generales del sistema.
     * Reemplaza: las consultas de conteo del panel admin legacy.
     */
    public function index(Request $request): JsonResponse
    {
        // ── Conteos generales ──
        $totalUsuarios      = User::count();
        $totalAlumnos       = User::alumnos()->count();
        $totalProfesores    = User::profesores()->count();
        $totalAdmins        = User::admins()->count();
        $totalObjetos       = Objeto::count();
        $totalReportes      = Reporte::count();
        $totalPublicaciones = Publicacion::count();

        // ── Objetos por tipo ──
        $objetosPorTipo = Objeto::select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        // ── Reportes por tipo ──
        $reportesPorTipo = Reporte::select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        // ── Registros recientes (últimos 7 días) ──
        $objetosRecientes  = Objeto::where('created_at', '>=', now()->subDays(7))->count();
        $reportesRecientes = Reporte::where('created_at', '>=', now()->subDays(7))->count();
        $usuariosRecientes = User::where('created_at', '>=', now()->subDays(7))->count();

        // ── Objetos por mes (últimos 6 meses) ──
        $objetosPorMes = Objeto::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // ── Reportes por mes (últimos 6 meses) ──
        $reportesPorMes = Reporte::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        return response()->json([
            'success' => true,
            'data'    => [
                'conteos' => [
                    'usuarios'      => $totalUsuarios,
                    'alumnos'       => $totalAlumnos,
                    'profesores'    => $totalProfesores,
                    'administradores' => $totalAdmins,
                    'objetos'       => $totalObjetos,
                    'reportes'      => $totalReportes,
                    'publicaciones' => $totalPublicaciones,
                ],
                'objetos_por_tipo'  => $objetosPorTipo,
                'reportes_por_tipo' => $reportesPorTipo,
                'recientes_7_dias' => [
                    'objetos'  => $objetosRecientes,
                    'reportes' => $reportesRecientes,
                    'usuarios' => $usuariosRecientes,
                ],
                'objetos_por_mes'  => $objetosPorMes,
                'reportes_por_mes' => $reportesPorMes,
            ],
        ]);
    }
}
