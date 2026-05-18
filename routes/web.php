<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Looking For
|--------------------------------------------------------------------------
| Todas las vistas son servidas por Blade.
| La lógica de negocio se consume via AJAX desde /api/v1.
*/

// ── Páginas públicas ──
Route::get('/', fn() => view('inicio'))->name('inicio');
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::get('/servicios', fn() => view('servicios'))->name('servicios');

// ── Páginas de usuario autenticado ──
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
Route::get('/objetos', fn() => view('objetos'))->name('objetos');
Route::get('/reportes', fn() => view('reportes'))->name('reportes');
Route::get('/publicaciones', fn() => view('publicaciones'))->name('publicaciones');
Route::get('/perfil', fn() => view('perfil'))->name('perfil');

// ── Páginas de admin ──
Route::prefix('admin')->name('admin.web.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/usuarios', fn() => view('admin.usuarios'))->name('usuarios');
    Route::get('/objetos', fn() => view('admin.objetos'))->name('objetos');
    Route::get('/reportes', fn() => view('admin.reportes'))->name('reportes');
    Route::get('/publicaciones', fn() => view('admin.publicaciones'))->name('publicaciones');
});
