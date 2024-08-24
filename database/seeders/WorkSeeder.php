<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Period;
use App\Models\Subject;
use App\Models\Work;
use App\Models\WorkStatus;
use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workStatuses = WorkStatus::all();
        $periods = Period::all();
        $workTypes = WorkType::all();
        $subjects = Subject::all();

        foreach ($periods as $period) {

            $classrooms = Classroom::where('period_id', $period->id)->get();

            foreach ($classrooms as $classroom) {
                for ($i = 0; $i < 20; $i++) {
                    Work::factory()->create([
                        'work_type_id' => $workTypes->random()->id,
                        'subject_id' => $subjects->random()->id,
                        'classroom_id' => $classroom->id,
                        'work_status_id' => $workStatuses->random()->id,
                    ]);
                }
            }
        }
    }
}
