<?php

namespace App\Policies;

use App\Models\Appreciation;
use App\Models\User;

class AppreciationPolicy
{
    use TraitCheckAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyAppreciation');
    }

    public function view(User $user, Appreciation $appreciation): bool
    {
        return $this->isAuthorized($user, 'viewAppreciation');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createAppreciation');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appreciation $appreciation): bool
    {
        return $this->isAuthorized($user, 'updateAppreciation');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appreciation $appreciation): bool
    {
        return $this->isAuthorized($user, 'deleteAppreciation');
    }
}
