<?php

namespace App\Repositories;

use App\Models\Period;
use Illuminate\Database\Eloquent\Collection;

class PeriodRepository
{
    public function all(): Collection
    {
        return Period::orderBy('start_date', 'desc')->get();
    }

    public function insert(School $school, array $data): Period
    {
        $period = new Period();
        $period->fill($data);
        $period->is_current = array_key_exists('is_current', $data) ? true : false;
        $period->school_id = $school->id;
        $period->save();

        if (array_key_exists('is_current', $data)) {
            $this->resetOtherPeriods($school, $period);
        }

        return $period;
    }

    public function update(School $school, Period $period, array $data): Period
    {
        $period->fill($data);
        $period->is_current = array_key_exists('is_current', $data) ? true : false;
        $period->save();

        if (array_key_exists('is_current', $data)) {
            $this->resetOtherPeriods($school, $period);
        }

        return $period;
    }

    public function delete(Period $period): void
    {
        $period->delete();
    }

    public function resetOtherPeriods(School $school, Period $period): void
    {
        Period::where('school_id', $school->id)
            ->where('id', '<>', $period->id)
            ->update([
                'is_current' => false,
            ]);
    }

    public function getCurrentPeriod(School $school): ?Period
    {
        return Period::where('school_id', $school->id)->where('is_current', true)->first();
    }
}
