<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use Illuminate\Auth\Access\Response;

class WorkPolicy
{
    use TraitCheckAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAuthorized($user, 'viewAnyWork');
    }

    public function view(User $user, Work $work): bool
    {
        return $this->isAuthorized($user, 'viewWork');
    }

    public function create(User $user): bool
    {
        return $this->isAuthorized($user, 'createWork');
    }

    public function update(User $user, Work $work): bool
    {
        return $this->isAuthorized($user, 'updateWork');
    }

    public function delete(User $user, Work $work): bool
    {
        return $this->isAuthorized($user, 'deleteWork');
    }
}
