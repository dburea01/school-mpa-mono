<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

class UserGroupPolicy
{
    use TraitCheckAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyUserGroup');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createUserGroup');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserGroup $userGroup): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $this->isAuthorized($user, 'deleteUserGroup');
    }
}
