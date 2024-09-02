<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Period;
use App\Models\RoleTask;
use App\Models\Subject;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkStatus;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyWorkTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Period $period;

    private Work $work;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->period = Period::factory()->create();
        $this->work = $this->buildWork($this->period);
        $this->url = '/periods/'.$this->period->id.'/works';
    }

    public function test_you_must_be_authenticated_to_access_classrooms(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_works(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_works(): void
    {
        RoleTask::where('task_id', 'viewAnyWork')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyWork', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_work(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->work->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_work(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewWork')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->work->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewWork', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->work->id)->assertOK();
    }

    public function test_an_admin_can_create_a_work(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_work(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createWork')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createWork', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_work(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->work->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_work(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateWork')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->work->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->work->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateWork', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->work->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->work->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_work(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->work->id)->assertRedirect('periods/'.$this->period->id.'/works');
    }

    public function test_an_authorized_user_can_delete_a_work(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteWork')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->work->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteWork', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->work->id)->assertRedirect('periods/'.$this->period->id.'/works');
    }

    public function buildWork(Period $period): Work
    {
        $classroom = Classroom::factory()->create(['period_id' => $period->id]);
        $subject = Subject::factory()->create();
        $workType = WorkType::factory()->create();
        $workStatus = WorkStatus::firstOrCreate();

        return Work::factory()->create([
            'classroom_id' => $classroom->id,
            'work_type_id' => $workType->id,
            'subject_id' => $subject->id,
            'work_status_id' => $workStatus->id,
        ]);
    }
}
