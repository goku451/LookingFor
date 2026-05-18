<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Solo admin puede ver la lista de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Un usuario puede verse a sí mismo, o un admin puede ver a cualquiera.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->is_admin;
    }

    /**
     * Solo admin puede crear usuarios.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Un usuario puede editarse a sí mismo, o un admin puede editar a cualquiera.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->is_admin;
    }

    /**
     * Solo admin puede eliminar usuarios.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->is_admin;
    }
}
