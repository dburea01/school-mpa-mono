<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    use TraitCheckAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyAssignment');
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $this->isAuthorized($user, 'viewAssignment');
    }

    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createAssignment');
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $this->isAuthorized($user, 'updateAssignment');
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->isAuthorized($user, 'deleteAssignment');
    }
}
