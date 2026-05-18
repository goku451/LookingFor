<?php

namespace App\Policies;

use App\Models\Publicacion;
use App\Models\User;

class PublicacionPolicy
{
    /**
     * Cualquier usuario autenticado puede ver la lista.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Cualquier usuario autenticado puede ver una publicación.
     */
    public function view(User $user, Publicacion $publicacion): bool
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
    public function update(User $user, Publicacion $publicacion): bool
    {
        return $user->id === $publicacion->user_id || $user->is_admin;
    }

    /**
     * Solo el dueño o un admin puede eliminar.
     */
    public function delete(User $user, Publicacion $publicacion): bool
    {
        return $user->id === $publicacion->user_id || $user->is_admin;
    }
}
