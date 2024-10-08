<?php

namespace App\Repositories;

use App\Models\Result;
use App\Models\User;
use App\Models\Work;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResultRepository
{
    public function getUsersWithResults(Work $work, string $sort, string $direction): Collection
    {

        // get the assigned users + their results
        $query = DB::table('users')
            ->join('assignments', function (JoinClause $join) use ($work) {
                $join->on('assignments.user_id', 'users.id')
                    ->where('users.role_id', 'STUDENT')
                    ->where('assignments.classroom_id', $work->classroom_id);
            })
            ->leftjoin('results', function (JoinClause $join) use ($work) {
                $join->on('results.user_id', 'users.id')
                    ->where('results.work_id', $work->id);
            })
            ->leftjoin('appreciations', 'appreciations.id', 'results.appreciation_id')

            ->select(
                'users.id as user_id',
                'users.last_name',
                'users.first_name',
                'users.birth_date',
                'users.gender_id',
                'results.appreciation_id',
                'appreciations.name as appreciation_name',
                'results.id as result_id',
                'results.note',
                'results.comment',
                'results.is_absent'
            );

        if ($sort == 'name') {
            $query->orderBy('last_name', $direction)->orderBy('first_name', $direction);
        }

        if ($sort == 'note') {
            $query->orderBy('note', $direction);
        }

        if ($sort == 'appreciation') {
            $query->orderBy('appreciation_id', $direction);
        }

        return $query->get();
    }

    /** @param array<string,string> $data */
    public function insert(Work $work, array $data): Result
    {
        $result = new Result();

        $result->work_id = $work->id;
        $result->user_id = $data['user_id'];
        $result->is_absent = (int) $data['is_absent'];

        if ($data['is_absent'] == 0) {
            $result->fill($data);
        } else {
            $result->note = null;
            $result->appreciation_id = null;
            $result->comment = null;
        }

        $result->save();

        return $result;
    }

    /** @param array<string,string> $data */
    public function update(Result $result, array $data): Result
    {
        $result->is_absent = (int) $data['is_absent'];

        if ($data['is_absent'] == 0) {
            $result->fill($data);
        } else {
            $result->note = null;
            $result->appreciation_id = null;
            $result->comment = null;
        }
        $result->save();

        return $result;
    }

    public function delete(Result $result): void
    {
        $result->delete();
    }

    public function deleteResultOneUserOneWork(Work $work, User $user): void
    {
        Result::where('work_id', $work->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    /** @param array<string,string> $request */
    public function getResultsByUser(User $user, array $request): LengthAwarePaginator
    {

        $query = DB::table('results')
            ->join('works', 'results.work_id', 'works.id')
            ->leftJoin('appreciations', function (JoinClause $join) {
                $join->on('results.appreciation_id', 'appreciations.id');
            })
            ->leftJoin('subjects', function (JoinClause $join) {
                $join->on('works.subject_id', 'subjects.id');
            })
            ->leftJoin('classrooms', function (JoinClause $join) {
                $join->on('works.classroom_id', 'classrooms.id');
            })
            ->leftJoin('work_types', function (JoinClause $join) {
                $join->on('works.work_type_id', 'work_types.id');
            })
            ->where('results.user_id', $user->id)
            ->when(isset($request['search']), function (QueryBuilder $query) use ($request) {
                $query->where('works.title', 'like', '%' . $request['search'] . '%');
            })
            ->when(isset($request['subject_id']), function (QueryBuilder $query) use ($request) {
                $query->where('works.subject_id', $request['subject_id']);
            })
            ->when(isset($request['classroom_id']), function (QueryBuilder $query) use ($request) {
                $query->where('works.classroom_id', $request['classroom_id']);
            })
            ->when(isset($request['work_type_id']), function (QueryBuilder $query) use ($request) {
                $query->where('works.work_type_id', $request['work_type_id']);
            })
            ->when(isset($request['period_id']), function (QueryBuilder $query) use ($request) {
                $query->where('classrooms.period_id', $request['period_id']);
            })
            ->select([
                'results.id as result_id',
                'results.note as result_note',
                'results.comment as result_comment',
                'results.appreciation_id as result_appreciation_id',
                'results.created_at as result_created_at',
                'results.is_absent as result_is_absent',
                'appreciations.short_name as appreciation_short_name',
                'appreciations.name as appreciation_name',
                'subjects.short_name as subject_short_name',
                'subjects.name as subject_name',
                'classrooms.short_name as classroom_short_name',
                'work_types.short_name as work_type_short_name',
                'work_types.name as work_type_name',
                'works.title as work_title',
                'works.comment as work_comment',
                'works.instruction as work_instruction',
                'works.expected_at as work_expected_at',
            ]);

        return $query->paginate();

        /*
        return Result::where('school_id', $school->id)
            ->where('user_id', $user->id)
            ->with('work')
            ->whereHas('work', function (Builder $query) use ($school, $request) {
                $query->where('school_id', $school->id)
                    ->orderBy('expected_at')
                    ->when(isset($request['search']), function ($query) use ($request) {
                        $query->where('title', 'ilike', '%'.$request['search'].'%');
                    })
                    ->with(['subject' => function (Builder $query) use ($school) {
                        $query->where('school_id', $school->id);
                    }])
                    ->with(['workType' => function (Builder $query) use ($school) {
                        $query->where('school_id', $school->id);
                    }])
                    ->with(['classroom' => function (Builder $query) use ($school) {
                        $query->where('school_id', $school->id);
                    }])
                    ->with('workStatus');
            })
            ->paginate();

        return Result::where('school_id', $school->id)
            ->where('user_id', $user->id)
            ->with(['work' => function (Builder $query) use ($school, $request) {
                $query->where('school_id', $school->id)
                    ->orderBy('expected_at')
                    ->when(isset($request['search']), function ($query) use ($request) {
                        $query->where('title', 'ilike', '%'.$request['search'].'%');
                    })
                    ->with(['subject' => function (Builder $query) use ($school) {
                        $query->where('school_id', $school->id);
                    }])
                    ->with(['workType' => function (Builder $query) use ($school) {
                        $query->where('school_id', $school->id);
                    }])
                    ->with(['classroom' => function (Builder $query) use ($school) {
                        $query->where('school_id', $school->id);
                    }])
                    ->with('workStatus');
            }])
            ->paginate();
        */
    }
}
