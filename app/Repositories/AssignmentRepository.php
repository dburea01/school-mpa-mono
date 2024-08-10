<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository
{
    public function index(?string $classroomId, string $roleId): Collection
    {

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
