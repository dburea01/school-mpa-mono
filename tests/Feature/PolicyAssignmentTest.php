<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Period;
use App\Models\RoleTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Period $period;

    private Classroom $classroom;

    private Assignment $assignment;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->period = Period::factory()->create();
        $this->classroom = Classroom::factory()->create(['period_id' => $this->period->id]);
        $this->assignment = Assignment::factory()->create([
            'classroom_id' => $this->classroom->id,
            'user_id' => $this->userNotAdmin->id,
        ]);
        $this->url = '/classrooms/'.$this->classroom->id.'/assignments';
    }

    public function test_you_must_be_authenticated_to_access_assignments_of_a_classroom(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_assignments_of_a_classroom(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_assignments_of_a_classroom(): void
    {
        RoleTask::where('task_id', 'viewAnyAssignment')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyAssignment', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_an_assignment(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->assignment->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_an_assignment(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewAssignment')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->assignment->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAssignment', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->assignment->id)->assertOK();
    }

    public function test_an_admin_can_create_an_assignment(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_an_assignment(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createAssignment')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createAssignment', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_an_assignment(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->assignment->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_an_assignment(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateAssignment')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->assignment->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->assignment->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateAssignment', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->assignment->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->assignment->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_an_assignment(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->assignment->id)->assertRedirect();
    }

    public function test_an_authorized_user_can_delete_an_assignment(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteAssignment')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->assignment->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteAssignment', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->assignment->id)->assertRedirect();
    }
}
