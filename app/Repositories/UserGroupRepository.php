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
    public function allUsersOfAGroup(Group $group): Group|null
    {
        return Group::where('id', $group->id)->with('users')->first();
    }

    public function insert(School $school, Group $group, string $userId): UserGroup
    {
        $userGroup = new UserGroup();
        $userGroup->school_id = $school->id;
        $userGroup->group_id = $group->id;
        $userGroup->user_id = $userId;
        $userGroup->save();

        return $userGroup;
    }

    public function delete(School $school, Group $group, User $user): void
    {
        DB::table('user_groups')
            ->where('school_id', $school->id)
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    public function getStudentsFromOneParent(School $school, User $user): mixed
    {

        /** @var \App\Models\UserGroup $userGroup */
        $userGroup = UserGroup::where('school_id', $school->id)
            ->where('user_id', $user->id)
            ->first();

        $studentsOfTheGroup = Group::where('school_id', $school->id)
            ->where('id', $userGroup->group_id)
            ->with(['users' => function (Builder $query) use ($school) {
                $query->where('user_groups.school_id', $school->id)
                    ->where('users.role_id', 'STUDENT')
                    ->orderBy('users.last_name')
                    ->orderBy('users.first_name');
            }])
            ->get();

        if ($studentsOfTheGroup->count() != 0) {
            return $studentsOfTheGroup->pluck('users')[0];
        } else {
            return new Collection();
        }
    }
}
