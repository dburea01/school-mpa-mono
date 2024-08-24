<?php

namespace App\Repositories;

use App\Models\Period;
use App\Models\School;
use App\Models\Work;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as Builder2;
use Illuminate\Support\Facades\Auth;

class WorkRepository
{
    public function all(Period $period, array $request): Paginator
    {
        $classroomRepository = new ClassroomRepository();

        /*
        if (Auth::user()) {
            $authorizedClassrooms = $classroomRepository->getAuthorizedClassrooms(Auth::user(), $school, $period)->pluck('id')->toArray();
        } else {
            $authorizedClassrooms = [];
        }

        $subjectRepository = new SubjectRepository();

        $authorizedSubjects = [];
        if (Auth::user()) {
            $authorizedSubjects = $subjectRepository->getAuthorizedSubjects(Auth::user(), $school, $period)->pluck('id')->toArray();
        }
        */

        /*
        $query = Work::with(['classroom' => function (Builder $queryPeriod) use ($period) {
            $queryPeriod->where('period_id', $period->id);
        }])
            */
        $query = Work::with(['classroom'])
            ->with(['subject', 'workType', 'workStatus'])
            ->orderBy('expected_at');

        $query
            ->when(isset($request['title']), function ($q) use ($request) {
                return $q->where(function (Builder2 $query) use ($request) {
                    return $query->where('title', 'like', '%' . $request['title'] . '%')
                        ->orWhere('description', 'like', '%' . $request['title'] . '%');
                });
            })
            ->when(isset($request['work_status_id']), function ($q) use ($request) {
                return $q->where('work_status_id', $request['work_status_id']);
            })
            ->when(isset($request['work_type_id']), function ($q) use ($request) {
                return $q->where('work_type_id', $request['work_type_id']);
            })
            ->when(isset($request['classroom_id']), function ($q) use ($request) {
                return $q->where('classroom_id', $request['classroom_id']);
            })
            ->when(isset($request['subject_id']), function ($q) use ($request) {
                return $q->where('subject_id', $request['subject_id']);
            });

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
