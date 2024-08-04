<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        $users = User::all();
        $students = $users->filter(function ($user) {
            return $user->role_id == 'STUDENT';
        });
        $teachers = $users->filter(function ($user) {
            return $user->role_id == 'TEACHER';
        });

        foreach ($classrooms as $classroom) {

            $students = $students->random(30);
            $teachers = $teachers->random(5);

            foreach ($students as $student) {
                Assignment::factory()->create([
                    'classroom_id' => $classroom->id,
                    'user_id' => $student->id,
                    'is_main_teacher' => false,
                    'comment' => fake()->boolean() ? fake()->sentence() : null,
                ]);
            }

            foreach ($teachers as $teacher) {
                Assignment::factory()->create([
                    'classroom_id' => $classroom->id,
                    'user_id' => $teacher->id,
                    'subject_id' => $subjects->random()->id,
                    'is_main_teacher' => fake()->boolean(50) ? true : false,
                    'comment' => fake()->boolean(50) ? fake()->sentence() : null,
                ]);
            }
        }
    }
}
