<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Listar todos los roles.
     * Útil para llenar selects/dropdowns en el frontend.
     */
    public function index(): JsonResponse
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'data'    => RoleResource::collection($roles),
        ]);
    }

    /**
     * Ver un rol específico con sus usuarios.
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
        ]);
    }
}
