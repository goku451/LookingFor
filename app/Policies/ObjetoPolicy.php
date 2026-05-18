<?php

namespace App\Policies;

use App\Models\Objeto;
use App\Models\User;

class ObjetoPolicy
{
    /**
     * Cualquier usuario autenticado puede ver la lista.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Cualquier usuario autenticado puede ver un objeto.
     */
    public function view(User $user, Objeto $objeto): bool
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
    public function update(User $user, Objeto $objeto): bool
    {
        return $user->id === $objeto->user_id || $user->is_admin;
    }

    /**
     * Solo el dueño o un admin puede eliminar.
     */
    public function delete(User $user, Objeto $objeto): bool
    {
        return $user->id === $objeto->user_id || $user->is_admin;
    }
}
