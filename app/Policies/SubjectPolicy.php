<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    use TraitCheckAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnySubject');
    }

    public function view(User $user, Subject $subject): bool
    {
        return $this->isAuthorized($user, 'viewSubject');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createSubject');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subject $subject): bool
    {
        return $this->isAuthorized($user, 'updateSubject');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subject $subject): bool
    {
        return $this->isAuthorized($user, 'deleteSubject');
    }
}
