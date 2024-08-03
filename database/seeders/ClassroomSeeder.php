<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Period;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = Period::all();

        foreach ($periods as $period) {
            Classroom::factory()->count(10)->create([
                'period_id' => $period->id,
            ]);
        }
    }
}
