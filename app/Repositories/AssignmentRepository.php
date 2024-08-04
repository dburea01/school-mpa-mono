<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository
{
    public function index(array $request): Collection
    {
        $query = Assignment::with(['user', 'subject', 'classroom']);

        $query->when(isset($request['user_id']), function ($q) use ($request) {
            return $q->where('user_id', $request['user_id']);
        });

        $query->when(isset($request['classroom_id']), function ($q) use ($request) {
            return $q->where('classroom_id', $request['classroom_id']);
        });

        $query->when(isset($request['subject_id']), function ($q) use ($request) {
            return $q->where('subject_id', $request['subject_id']);
        });

        $assignments = $query->get();

        if (isset($request['role_id']) && $request['role_id'] != '') {
            $roleId = $request['role_id'];

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
