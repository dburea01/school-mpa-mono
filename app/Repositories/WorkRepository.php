<?php

namespace App\Repositories;

use App\Models\Period;
use App\Models\School;
use App\Models\Work;
use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as Builder2;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkRepository
{
    public function all(Period $period, array $request): Paginator
    {
        $query = DB::table('works')
            ->join('work_statuses', 'works.work_status_id', 'work_statuses.id')
            ->join('work_types', 'works.work_type_id', 'work_types.id')
            ->join('classrooms', function (JoinClause $join) use ($period) {
                $join->on('works.classroom_id', 'classrooms.id')
                    ->where('classrooms.period_id', $period->id);
            })
            ->join('subjects', 'works.subject_id', 'subjects.id')
            ->select(
                'works.id',
                'works.title',
                'works.description',
                'works.estimated_duration',
                'works.expected_at',

                'subjects.id as subject_id',
                'subjects.short_name as subject_short_name',

                'classrooms.id as classroom_id',
                'classrooms.name as classroom_name',
                'classrooms.short_name as classroom_short_name',

                'work_types.id as work_type_id',
                'work_types.short_name as work_type_short_name',

                'work_statuses.id as work_status_id',
                'work_statuses.name as work_status_name'
            )
            ->orderBy('espected_at', 'desc');



        if (isset($request['title']) && filled($request['title'])) {
            $query->where(function (Builder $query2) use ($request) {
                $query2->where('works.title', 'like', '%' . $request['title'] . '%')
                    ->orWhere('works.description', 'like', '%' . $request['title'] . '%');
            });
        }

        if (isset($request['classroom_id']) && filled($request['classroom_id'])) {
            $query->where('works.classroom_id', $request['classroom_id']);
        }

        if (isset($request['subject_id']) && filled($request['subject_id'])) {
            $query->where('works.subject_id', $request['subject_id']);
        }

        if (isset($request['work_type_id']) && filled($request['work_type_id'])) {
            $query->where('works.work_type_id', $request['work_type_id']);
        }

        if (isset($request['work_status_id']) && filled($request['work_status_id'])) {
            $query->where('works.work_status_id', $request['work_status_id']);
        }

        return $query->paginate();
    }

    public function insert(School $school, array $data): Work
    {
        $work = new Work();
        $work->fill($data);
        $work->school_id = $school->id;
        $work->save();

        return $work;
    }

    public function update(Work $work, array $data): Work
    {
        $work->fill($data);
        $work->save();

        return $work;
    }

    public function delete(Work $work): void
    {
        $work->delete();
    }
}
