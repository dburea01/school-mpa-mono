<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

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
            'login_status_id' => 'VALIDATED',
            'role_id' => 'ADMIN',
        ]);

        // create some teachers
        $teachers = User::factory()->count(10)->create([
            'role_id' => 'TEACHER',
        ]);

        // create some parents
        $parents = User::factory()->count(100)->create([
            'role_id' => 'PARENT',
        ]);
    }
}
