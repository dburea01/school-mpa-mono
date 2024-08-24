<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkType;

class WorkTypePolicy
{
    use TraitCheckAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyWorkType');
    }

    public function view(User $user, WorkType $workType): bool
    {
        return $this->isAuthorized($user, 'viewWorkType');
    }

    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createWorkType');
    }

    public function update(User $user, WorkType $workType): bool
    {
        return $this->isAuthorized($user, 'updateWorkType');
    }

    public function delete(User $user, WorkType $workType): bool
    {
        return $this->isAuthorized($user, 'deleteWorkType');
    }
}
