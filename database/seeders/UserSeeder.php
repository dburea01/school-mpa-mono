<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        // create 1 administrateur
        $admin = User::factory()->create([
            'login_status_id' => 'VALIDATED'
        ]);
        $admin->assignRole('administrateur');

        // create some teachers
        $teachers = User::factory()->count(10)->create();
        foreach ($teachers as $teacher) {
            $teacher->assignRole('enseignant');
        }

        // create some parents
        $parents = User::factory()->count(10)->create();
        foreach ($parents as $parent) {
            $parent->assignRole('parent');
        }
    }
}
