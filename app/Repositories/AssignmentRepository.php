<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssignmentRepository
{
    public function index(?string $classroomId, ?string $roleId): Collection
    {

        $query = DB::table('users')
            ->join('assignments', 'assignments.user_id', 'users.id')
            ->join('roles', 'users.role_id', 'roles.id')
            ->join('classrooms', 'classrooms.id', 'assignments.classroom_id')
            ->leftjoin('subjects', 'subjects.id', 'assignments.subject_id')
            ->select(
                'assignments.id',
                'users.id as user_id',
                'users.last_name',
                'users.first_name',
                'users.gender_id',
                'assignments.id as assignment_id',
                'roles.id as role_id',
                'roles.name as role_name',
                'classrooms.id as classroom_id',
                'classrooms.name as classroom_name',
                'classrooms.short_name as classroom_short_name',
                'subjects.id as subject_id',
                'subjects.name as subject_name'
            )
            ->orderBy('last_name')->orderBy('first_name');

        if (isset($roleId) and $roleId != '') {
            $query->where('users.role_id', $roleId);
        }
        if (isset($classroomId) and $classroomId != '') {
            $query->where('assignments.classroom_id', $classroomId);
        }

        return $query->get();

        /*
        $query = Assignment::with(['user', 'subject', 'classroom']);

        $query->when(isset($classroomId), function ($q) use ($classroomId) {
            return $q->where('classroom_id', $classroomId);
        });

        $assignments = $query->get();

        if (isset($roleId) && $roleId != '') {
            $assignments = $assignments->filter(function ($assignment) use ($roleId) {
                
                return $assignment->user->role_id == $roleId;
            });
        }

        return $assignments->sortBy('user.last_name');
        */
    }

    /** @param array<string,string> $data */
    public function insert(array $data): Assignment
    {
        $assignment = new Assignment();
        $assignment->fill($data);
        $assignment->save();

        return $assignment;
    }

    /** @param array<string,string> $data */
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

    public function repartition_assignment_by_role(Classroom $classroom): Collection
    {
        // repartition by role
        $repartitionByRole = DB::table('users')
            ->join('assignments', 'assignments.user_id', 'users.id')
            ->join('roles', 'users.role_id', 'roles.id')
            ->join('classrooms', 'classrooms.id', 'assignments.classroom_id')
            ->leftjoin('subjects', 'subjects.id', 'assignments.subject_id')
            ->where('assignments.classroom_id', $classroom->id)
            ->select('roles.name as role_name', DB::raw('count(roles.id) as quantity'))
            ->groupBy('roles.id')
            ->get();

        return $repartitionByRole;
    }

    public function repartition_assignment_by_gender(Classroom $classroom): Collection
    {
        $repartitionByGender = DB::table('users')
            ->join('assignments', 'assignments.user_id', 'users.id')
            ->where('assignments.classroom_id', $classroom->id)
            ->where('users.role_id', 'STUDENT')
            ->select('users.gender_id', DB::raw('count(users.gender_id) as quantity'))
            ->groupBy('users.gender_id')
            ->get();

        return $repartitionByGender;
    }
}
