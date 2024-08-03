<?php

namespace Tests\Feature;

use App\Models\RoleTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private User $student;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->student = User::factory()->create(['role_id' => 'STUDENT']);
        $this->url = '/users';
    }

    public function test_you_must_be_authenticated_to_access_users(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_users(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_users(): void
    {
        RoleTask::where('task_id', 'viewAnyUser')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyUser', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_an_user(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->student->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_an_user(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewUser')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->student->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewUser', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->student->id)->assertOK();
    }

    public function test_an_admin_can_create_an_user(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_an_user(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createUser')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createUser', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_an_user(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->student->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_an_user(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateUser')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->student->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->student->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateUser', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->student->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->student->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_an_user(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->student->id)->assertRedirect('users');
    }

    public function test_an_authorized_user_can_delete_an_user(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteUser')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->student->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteUser', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->student->id)->assertRedirect('users');
    }
}
