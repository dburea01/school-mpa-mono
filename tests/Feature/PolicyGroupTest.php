<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\RoleTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyGroupTest extends TestCase
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
        $this->url = '/groups';
    }

    public function test_you_must_be_authenticated_to_access_groups(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_groups(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_groups(): void
    {
        RoleTask::where('task_id', 'viewAnyGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_group(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->group->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_period(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->group->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->group->id)->assertOK();
    }

    public function test_an_admin_can_create_a_group(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_group(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_group(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->group->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_group(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->group->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->group->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->group->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->group->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_group(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->group->id)->assertRedirect('groups');
    }

    public function test_an_authorized_user_can_delete_a_group(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteGroup')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->group->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteGroup', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->group->id)->assertRedirect('groups');
    }
}
