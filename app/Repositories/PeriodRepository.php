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

    /** @param array<string,string> $data */
    public function insert(array $data): Period
    {
        $period = new Period();
        $period->fill($data);
        $period->is_current = array_key_exists('is_current', $data) ? true : false;
        $period->save();

        if (array_key_exists('is_current', $data)) {
            $this->resetOtherPeriods($period);
        }

        return $period;
    }

    /** @param array<string,string> $data */
    public function update(Period $period, array $data): Period
    {
        $period->fill($data);
        $period->is_current = array_key_exists('is_current', $data) ? true : false;
        $period->save();

        if (array_key_exists('is_current', $data)) {
            $this->resetOtherPeriods($period);
        }

        return $period;
    }

    public function delete(Period $period): void
    {
        $period->delete();
    }

    public function resetOtherPeriods(Period $period): void
    {
        Period::where('id', '<>', $period->id)
            ->update([
                'is_current' => false,
            ]);
    }

    public function getCurrentPeriod(): ?Period
    {
        return Period::where('is_current', true)->first();
    }
}
