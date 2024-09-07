<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;

class ResultPolicy
{
    use TraitCheckAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyResult');
    }

    public function view(User $user, Result $result): bool
    {
        return $this->isAuthorized($user, 'viewResult');
    }

    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createResult');
    }

    public function update(User $user, Result $result): bool
    {
        return $this->isAuthorized($user, 'updateResult');
    }

    public function delete(User $user, Result $result): bool
    {
        return $this->isAuthorized($user, 'deleteResult');
    }
}
