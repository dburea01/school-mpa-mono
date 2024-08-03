<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;

class SubjectRepository
{
    public function all(): Collection
    {
        return Subject::orderBy('name')->get();
    }

    /** @param array<string,string> $data */
    public function insert(array $data): Subject
    {
        $subject = new Subject();
        $subject->fill($data);
        $subject->is_active = array_key_exists('is_active', $data) ? true : false;
        $subject->save();

        return $subject;
    }

    /** @param array<string,string> $data */
    public function update(Subject $subject, array $data): Subject
    {
        $subject->fill($data);
        $subject->is_active = array_key_exists('is_active', $data) ? true : false;
        $subject->save();

        return $subject;
    }

    public function delete(Subject $subject): void
    {
        $subject->delete();
    }
}
