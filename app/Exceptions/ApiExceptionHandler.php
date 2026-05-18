<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Registrar las excepciones de la API en el exception handler de Laravel 12.
     * Se llama desde bootstrap/app.php
     */
    public static function register(\Illuminate\Foundation\Configuration\Exceptions $exceptions): void
    {
        // ── 401: No autenticado ──
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado. Por favor inicia sesión.',
                ], 401);
            }
        });

        // ── 403: Sin permiso ──
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para acceder a este recurso.',
                ], 403);
            }
        });

        // ── 404: Modelo no encontrado ──
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $model = class_basename($e->getModel());
                $modelNames = [
                    'User'        => 'usuario',
                    'Objeto'      => 'objeto',
                    'Reporte'     => 'reporte',
                    'Publicacion' => 'publicación',
                    'Role'        => 'rol',
                ];
                $name = $modelNames[$model] ?? $model;

                return response()->json([
                    'success' => false,
                    'message' => "El {$name} solicitado no fue encontrado.",
                ], 404);
            }
        });

        // ── 404: Ruta no encontrada ──
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ruta solicitada no existe.',
                ], 404);
            }
        });

        // ── 405: Método no permitido ──
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Método HTTP no permitido para esta ruta.',
                ], 405);
            }
        });

        // ── 422: Validación ──
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // ── 429: Rate limit ──
        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demasiadas solicitudes. Intenta de nuevo más tarde.',
                ], 429);
            }
        });

        // ── 500: Error genérico (solo en producción oculta detalles) ──
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : 500;

                $response = [
                    'success' => false,
                    'message' => 'Error interno del servidor.',
                ];

                // En modo debug, incluir detalles
                if (config('app.debug')) {
                    $response['debug'] = [
                        'exception' => get_class($e),
                        'message'   => $e->getMessage(),
                        'file'      => $e->getFile(),
                        'line'      => $e->getLine(),
                    ];
                }

                return response()->json($response, $statusCode);
            }
        });
    }
}
