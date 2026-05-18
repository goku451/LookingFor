<?php

namespace App\Policies;

use App\Models\Reporte;
use App\Models\User;

class ReportePolicy
{
    /**
     * Cualquier usuario autenticado puede ver la lista.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Cualquier usuario autenticado puede ver un reporte.
     */
    public function view(User $user, Reporte $reporte): bool
    {
        return true;
    }

    /**
     * Cualquier usuario autenticado puede crear.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Solo el dueño o un admin puede editar.
     */
    public function update(User $user, Reporte $reporte): bool
    {
        return $user->id === $reporte->user_id || $user->is_admin;
    }

    /**
     * Solo el dueño o un admin puede eliminar.
     */
    public function delete(User $user, Reporte $reporte): bool
    {
        return $user->id === $reporte->user_id || $user->is_admin;
    }
}
