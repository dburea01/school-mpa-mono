<?php

namespace App\Repositories;

use App\Models\Period;
use App\Models\WorkType;
use Illuminate\Database\Eloquent\Collection;

class WorkTypeRepository
{
    public function all(): Collection
    {
        return WorkType::orderBy('name')->get();
    }

    /** @param array<string,string> $data */
    public function insert(array $data): WorkType
    {
        $workType = new WorkType();
        $workType->fill($data);
        $workType->is_active = array_key_exists('is_active', $data) ? true : false;
        $workType->save();

        return $workType;
    }

    /** @param array<string,string> $data */
    public function update(WorkType $workType, array $data): WorkType
    {
        $workType->fill($data);
        $workType->is_active = array_key_exists('is_active', $data) ? true : false;
        $workType->save();

        return $workType;
    }

    public function delete(WorkType $workType): void
    {
        $workType->delete();
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
