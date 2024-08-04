<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    use TraitCheckAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyClassroom');
    }

    public function view(User $user, Classroom $classroom): bool
    {
        return $this->isAuthorized($user, 'viewClassroom');
    }

    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createClassroom');
    }

    public function update(User $user, Classroom $classroom): bool
    {
        return $this->isAuthorized($user, 'updateClassroom');
    }

    public function delete(User $user, Classroom $classroom): bool
    {
        return $this->isAuthorized($user, 'deleteClassroom');
    }
}
