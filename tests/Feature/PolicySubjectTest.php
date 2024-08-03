<?php

namespace Tests\Feature;

use App\Models\RoleTask;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicySubjectTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Subject $subject;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->subject = Subject::firstOrCreate();
        $this->url = '/subjects';
    }

    public function test_you_must_be_authenticated_to_access_subjects(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_subjects(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_subjects(): void
    {
        RoleTask::where('task_id', 'viewAnySubject')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnySubject', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_subject(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->subject->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_subject(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewSubject')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->subject->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewSubject', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->subject->id)->assertOK();
    }

    public function test_an_admin_can_create_a_subject(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_subject(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createSubject')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createSubject', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_subject(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->subject->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_subject(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateSubject')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->subject->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->subject->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateSubject', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->subject->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->subject->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_subject(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->subject->id)->assertRedirect('subjects');
    }

    public function test_an_authorized_user_can_delete_a_subject(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteSubject')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->subject->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteSubject', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->subject->id)->assertRedirect('subjects');
    }
}
