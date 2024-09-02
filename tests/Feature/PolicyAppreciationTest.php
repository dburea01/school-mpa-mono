<?php

namespace Tests\Feature;

use App\Models\Appreciation;
use App\Models\RoleTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyAppreciationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Appreciation $appreciation;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->appreciation = Appreciation::firstOrCreate();
        $this->url = '/appreciations';
    }

    public function test_you_must_be_authenticated_to_access_appreciations(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_appreciations(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_appreciations(): void
    {
        RoleTask::where('task_id', 'viewAnyAppreciation')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyAppreciation', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_an_appreciation(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->appreciation->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_an_appreciation(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewAppreciation')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->appreciation->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAppreciation', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->appreciation->id)->assertOK();
    }

    public function test_an_admin_can_create_an_appreciation(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_an_appreciation(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createAppreciation')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createAppreciation', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_an_appreciation(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->appreciation->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_an_appreciation(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updateAppreciation')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->appreciation->id.'/edit')->assertForbidden();
        $this->put($this->url.'/'.$this->appreciation->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updateAppreciation', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->appreciation->id.'/edit')->assertOK();
        $this->put($this->url.'/'.$this->appreciation->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_an_appreciation(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url.'/'.$this->appreciation->id)->assertRedirect('appreciations');
    }

    public function test_an_authorized_user_can_delete_an_appreciation(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deleteAppreciation')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url.'/'.$this->appreciation->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deleteAppreciation', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url.'/'.$this->appreciation->id)->assertRedirect('appreciations');
    }
}
