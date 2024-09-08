<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Period;
use App\Models\Result;
use App\Models\RoleTask;
use App\Models\Subject;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkStatus;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyResultTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;
    private User $student;

    private Period $period;

    private Work $work;

    private Result $result;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->student = User::factory()->create(['role_id' => 'STUDENT']);
        $this->period = Period::factory()->create();
        $this->work = $this->buildWork($this->period);
        $this->result = Result::factory()->create([
            'work_id' => $this->work->id,
            'user_id' => $this->student->id
        ]);
        $this->url = '/works/' . $this->work->id . '/results';
    }

    public function test_you_must_be_authenticated_to_access_results(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_results(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_results(): void
    {
        RoleTask::where('task_id', 'viewAnyResult')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyResult', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_result(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url . '/' . $this->result->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_result(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewResult')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url . '/' . $this->result->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewResult', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url . '/' . $this->result->id)->assertOK();
    }

    public function test_an_admin_can_create_a_result(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url . '/create?user_id='.$this->student->id)->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_result(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createResult')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url . '/create?user_id='.$this->student->id)->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createResult', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url . '/create?user_id='.$this->student->id)->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_result(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url . '/' . $this->result->id . '/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_result(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateResult')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url . '/' . $this->result->id . '/edit')->assertForbidden();
        $this->put($this->url . '/' . $this->result->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateResult', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url . '/' . $this->result->id . '/edit')->assertOK();
        $this->put($this->url . '/' . $this->result->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_result(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url . '/' . $this->result->id)->assertRedirect('works/' . $this->work->id . '/results');
    }

    public function test_an_authorized_user_can_delete_a_result(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteResult')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url . '/' . $this->result->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteResult', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url . '/' . $this->result->id)->assertRedirect('works/' . $this->work->id . '/results');
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
