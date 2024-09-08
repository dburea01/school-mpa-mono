<?php

namespace Database\Seeders;

use App\Models\Appreciation;
use App\Models\Assignment;
use App\Models\Result;
use App\Models\Work;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

            
            $assignments = DB::table('users')
                ->join('assignments', function (JoinClause $join) use ($work) {
                    $join->on('assignments.user_id', 'users.id')
                        ->where('users.role_id', 'STUDENT')
                        ->where('assignments.classroom_id', $work->classroom_id);
                })->select('users.id as user_id')->get();

            /** @var Assignment $assignment */
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
            $resultRandom->is_absent = 1;
            $resultRandom->note = null;
            $resultRandom->appreciation_id = null;
            $resultRandom->comment = null;
            $resultRandom->save();
        }
    }
}
