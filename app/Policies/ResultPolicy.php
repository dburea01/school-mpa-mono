<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

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

    public function viewAnyStudentResult(User $user, User $student): bool
    {
        if ($user->id == $student->id) {
            return true;
        }

        if ($this->isAuthorized($user, 'viewAnyResult')) {
            return true;
        }

        $userGroup = DB::table('user_groups as user_groups_parent')
            ->join('user_groups as user_groups_student', 'user_groups_student.group_id', 'user_groups_parent.group_id')
            ->join('users as parents', function (JoinClause $join) use ($user) {
                $join->on('parents.id', 'user_groups_parent.user_id')
                    ->where('parents.role_id', 'PARENT')
                    ->where('parents.id', $user->id);
            })

            ->join('users as students', function (JoinClause $join) use ($student) {
                $join->on('students.id', 'user_groups_student.user_id')
                    ->where('students.role_id', 'STUDENT')
                    ->where('students.id', $student->id);
            })->count();

        if ($userGroup > 0) {
            return true;
        }

        return false;
    }
}
