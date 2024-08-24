<?php

namespace Tests\Feature;

use App\Models\RoleTask;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyWorkTypeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private WorkType $workType;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->workType = WorkType::factory()->create();
        $this->url = '/work-types';
    }

    public function test_you_must_be_authenticated_to_access_work_types(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_work_types(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_work_types(): void
    {
        RoleTask::where('task_id', 'viewAnyWorkType')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyWorkType', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_work_type(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->workType->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_work_type(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewWorkType')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->workType->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewWorkType', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->workType->id)->assertOK();
    }

    public function test_an_admin_can_create_a_work_type(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_work_type(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createWorkType')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createWorkType', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_work_type(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->workType->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_work_type(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateWorkType')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->workType->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->workType->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateWorkType', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->workType->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->workType->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_work_type(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->workType->id)->assertRedirect('work-types');
    }

    public function test_an_authorized_user_can_delete_a_work_type(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteWorkType')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->workType->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteWorkType', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->workType->id)->assertRedirect('work-types');
    }
}
