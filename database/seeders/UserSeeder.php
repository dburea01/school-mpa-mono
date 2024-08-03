<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Collection;
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

        $groups = Group::all();

        foreach ($groups as $group) {
            $parents = User::factory()->count(rand(1, 2))->create([
                'role_id' => 'PARENT',
                'last_name' => $group->name,
            ]);

            $this->createUserGroup($group, $parents);

            $students = User::factory()->count(random_int(1, 3))->create([
                'role_id' => 'STUDENT',
                'last_name' => $group->name,
            ]);

            $this->createUserGroup($group, $students);
        }
    }

    /**
     * @param  Collection<User>  $users
     */
    public function createUserGroup(Group $group, Collection $users): void
    {
        foreach ($users as $user) {
            UserGroup::factory()->create([
                'group_id' => $group->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
