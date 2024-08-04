<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Period;
use App\Models\RoleTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyClassroomTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Period $period;
    private Classroom $classroom;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->period = Period::factory()->create();
        $this->classroom = Classroom::factory()->create(['period_id' => $this->period->id]);
        $this->url = '/periods/'.$this->period->id.'/classrooms';
    }

    public function test_you_must_be_authenticated_to_access_classrooms(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_classrooms(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_classrooms(): void
    {
        RoleTask::where('task_id', 'viewAnyClassroom')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyClassroom', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_classroom(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->classroom->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_classroom(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewClassroom')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->classroom->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewClassroom', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->classroom->id)->assertOK();
    }

    public function test_an_admin_can_create_a_classroom(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_classroom(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createClassroom')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createClassroom', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_classroom(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->classroom->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_classroom(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateClassroom')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->classroom->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->classroom->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateClassroom', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->classroom->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->classroom->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_classroom(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->classroom->id)->assertRedirect('periods/'.$this->period->id.'/classrooms');
    }

    public function test_an_authorized_user_can_delete_a_classroom(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteClassroom')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->classroom->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteClassroom', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->classroom->id)->assertRedirect('periods/'.$this->period->id.'/classrooms');
    }
}
