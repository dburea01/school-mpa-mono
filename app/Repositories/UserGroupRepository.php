<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Group;
use App\Models\School;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserGroupRepository
{
    public function allUsersOfAGroup(Group $group): ?Group
    {
        return Group::where('id', $group->id)->with('users')->first();
    }

    public function insert(Group $group, string $userId): UserGroup
    {
        $userGroup = new UserGroup();
        $userGroup->group_id = $group->id;
        $userGroup->user_id = $userId;
        $userGroup->save();

        return $userGroup;
    }

    public function delete(Group $group, User $user): void
    {
        DB::table('user_groups')
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->delete();
    }
}
