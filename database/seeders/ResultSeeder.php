<?php

namespace Database\Seeders;

use App\Models\Appreciation;
use App\Models\Assignment;
use App\Models\Result;
use App\Models\Work;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $works = Work::all();
        $appreciations = Appreciation::all();

        foreach ($works as $work) {
            $assignments = Assignment::where('classroom_id', $work->classroom_id)
                ->with(['user' => function (Builder $query) {
                    $query->where('role_id', 'STUDENT');
                }])
                ->get();
            // dd($assignments);
            foreach ($assignments as $assignment) {
                Result::factory()->create([
                    'user_id' => $assignment->user_id,
                    'work_id' => $work->id,
                    'appreciation_id' => fake()->boolean() ? $appreciations->random()->id : null,
                ]);
            }

            // take one result and declare it absent
            $allResults = Result::where('work_id', $work->id)->get();

            $resultRandom = $allResults->random();
            $resultRandom->is_absent = true;
            $resultRandom->note = null;
            $resultRandom->appreciation_id = null;
            $resultRandom->comment = null;
            $resultRandom->save();
        }
    }
}
