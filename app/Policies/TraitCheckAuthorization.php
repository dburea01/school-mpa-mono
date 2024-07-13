<?php

namespace App\Policies;

use App\Models\RoleTask;
use App\Models\User;

trait TraitCheckAuthorization
{
    public function isAuthorized(User $user, string $taskId): bool
    {
        $roleTask = RoleTask::where('role_id', $user->role_id)->where('task_id', $taskId)->first();

        return $roleTask ? true : false;
    }
}
