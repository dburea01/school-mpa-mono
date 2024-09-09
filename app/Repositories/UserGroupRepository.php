<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Query\JoinClause;
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

    public function getStudentsOfParent(User $parent) {

        $students = DB::table('user_groups as user_groups_parent')
        ->join('user_groups as user_groups_student', 'user_groups_student.group_id', 'user_groups_parent.group_id')
        ->join('users as parents', function (JoinClause $join) use ($parent) {
            $join->on('parents.id', 'user_groups_parent.user_id')
                ->where('parents.role_id', 'PARENT')
                ->where('parents.id', $parent->id);
        })
        ->join('users as students', function (JoinClause $join) {
            $join->on('students.id', 'user_groups_student.user_id')
                ->where('students.role_id', 'STUDENT');
        })
        ->select('students.*')
        ->get();

        return $students;
    }
}
