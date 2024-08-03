<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Period;
use App\Models\RoleTask;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PolicyUserGroupTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Group $group;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->group = Group::factory()->create();
        $this->url = '/groups/'.$this->group->id.'/users';
    }

    public function test_you_must_be_authenticated_to_access_user_groups(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_user_of_a_group(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_users_of_a_group(): void
    {
        RoleTask::where('task_id', 'viewAnyUserGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyUserGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

   

   

    public function test_an_admin_can_add_an_user_to_a_group(): void
    {
        $this->actingAs($this->admin);
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_add_an_user_to_a_group(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createUserGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createUserGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_remove_an_user_from_a_group(): void
    {
        $user = User::factory()->create(['role_id' => 'STUDENT']);
        UserGroup::create([
            'group_id' => $this->group->id,
            'user_id' => $user->id
        ]);

        $this->actingAs($this->admin);
        $this->delete($this->url . '/'. $user->id)->assertRedirect($this->url.'?name=');
    }

    public function test_an_authorized_user_can_remove_an_user_from_a_group(): void
    {
        $user = User::factory()->create(['role_id' => 'STUDENT']);
        UserGroup::create([
            'group_id' => $this->group->id,
            'user_id' => $user->id
        ]);

        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteUserGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url . '/'. $user->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteUserGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url . '/'. $user->id)->assertRedirect($this->url.'?name=');
    }
}
