<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ObjetoController;
use App\Http\Controllers\Api\V1\PerfilController;
use App\Http\Controllers\Api\V1\PublicacionController;
use App\Http\Controllers\Api\V1\ReporteController;
use App\Http\Controllers\Api\V1\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\ObjetoController as AdminObjetoController;
use App\Http\Controllers\Api\V1\Admin\PublicacionController as AdminPublicacionController;
use App\Http\Controllers\Api\V1\Admin\ReporteController as AdminReporteController;
use App\Http\Controllers\Api\V1\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Api\V1\Admin\UsuarioController as AdminUsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Looking For
|--------------------------------------------------------------------------
| Prefijo: /api/v1
| Auth: Laravel Sanctum
*/

Route::prefix('v1')->group(function () {

    // ── Rutas públicas ──
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

    // ── Rutas protegidas ──
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

        // Perfil del usuario autenticado
        Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
        Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
        Route::put('/perfil/password', [PerfilController::class, 'cambiarPassword'])->name('perfil.password');

        // CRUD Usuario — sus propios objetos, reportes y publicaciones
        Route::apiResource('objetos', ObjetoController::class);
        Route::apiResource('reportes', ReporteController::class);
        Route::apiResource('publicaciones', PublicacionController::class);

        // ── Panel Admin ──
        Route::middleware('role:administrador')
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                // Dashboard con estadísticas
                Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

                // Gestión de roles (solo lectura)
                Route::get('roles', [AdminRoleController::class, 'index'])->name('roles.index');
                Route::get('roles/{role}', [AdminRoleController::class, 'show'])->name('roles.show');

                // CRUD completo
                Route::apiResource('usuarios', AdminUsuarioController::class);
                Route::apiResource('objetos', AdminObjetoController::class);
                Route::apiResource('reportes', AdminReporteController::class);
                Route::apiResource('publicaciones', AdminPublicacionController::class);
            });
    });
});
