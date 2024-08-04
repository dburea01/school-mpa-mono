<?php

namespace App\Repositories;

use App\Models\Classroom;
use App\Models\Period;
use Illuminate\Database\Eloquent\Collection;

class ClassroomRepository
{
    public function index(Period $period): Collection
    {
        return Classroom::where('period_id', $period->id)->orderBy('short_name')->get();
    }

    /** @param array<string,string> $data */
    public function insert(Period $period, array $data): Classroom
    {
        $classroom = new Classroom();
        $classroom->fill($data);
        $classroom->period_id = $period->id;
        $classroom->save();

        return $classroom;
    }

    /** @param array<string,string> $data */
    public function update(Classroom $classroom, array $data): Classroom
    {
        $classroom->fill($data);
        $classroom->save();

        return $classroom;
    }

    public function delete(Classroom $classroom): void
    {
        $classroom->delete();
    }
}
