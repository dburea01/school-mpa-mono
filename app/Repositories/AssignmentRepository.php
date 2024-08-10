<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Assignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssignmentRepository
{
    public function index(string $classroomId, ?string $roleId): Collection
    {

        $query = DB::table('users')
            ->join('assignments', 'assignments.user_id', 'users.id')
            ->join('roles', 'users.role_id', 'roles.id')
            ->leftjoin('subjects',  'subjects.id', 'assignments.subject_id')
            ->where('assignments.classroom_id', $classroomId)
            ->select(
                'users.id as user_id',
                'users.last_name',
                'users.first_name',
                'users.gender_id',
                'assignments.id as assignment_id',
                'roles.id as role_id',
                'roles.name as role_name',
                'subjects.id as subject_id',
                'subjects.name as subject_name'
            )
            ->orderBy('last_name')->orderBy('first_name');

        if (isset($roleId) and $roleId != '') {
            $query->where('users.role_id', $roleId);
        }

        return $query->get();

        $query = Assignment::with(['user', 'subject', 'classroom']);

        $query->when(isset($classroomId), function ($q) use ($classroomId) {
            return $q->where('classroom_id', $classroomId);
        });

        $assignments = $query->get();

        if (isset($roleId) && $roleId != '') {
            $assignments = $assignments->filter(function ($assignment) use ($roleId) {
                /** @phpstan-ignore-next-line */
                return $assignment->user->role_id == $roleId;
            });
        }

        return $assignments->sortBy('user.last_name');
    }

    public function insert(array $data): Assignment
    {
        $assignment = new Assignment();
        $assignment->fill($data);
        $assignment->save();

        return $assignment;
    }

    public function update(Assignment $assignment, array $data): Assignment
    {
        $assignment->fill($data);
        $assignment->save();

        return $assignment;
    }

    public function delete(Assignment $assignment): void
    {
        $assignment->delete();
    }
}
