<?php

namespace App\Policies;

use App\Models\Period;
use App\Models\User;

class PeriodPolicy
{
    use TraitCheckAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyPeriod');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createPeriod');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Period $period): bool
    {
        return $this->isAuthorized($user, 'updatePeriod');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Period $period): bool
    {
        return $this->isAuthorized($user, 'deletePeriod');
    }
}
