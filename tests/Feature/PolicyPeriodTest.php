<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\RoleTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PolicyPeriodTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $userNotAdmin;

    private Period $period;

    private User $teacher;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        $this->period = Period::factory()->create();
        $this->url = '/periods';
    }

    public function test_you_must_be_authenticated_to_access_periods(): void
    {
        $this->get($this->url)->assertRedirectToRoute('login');
    }

    public function test_an_admin_can_list_the_periods(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_authorized_user_can_list_the_periods(): void
    {
        RoleTask::where('task_id', 'viewAnyPeriod')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'viewAnyPeriod', 'role_id' => $this->userNotAdmin->role_id]);
        $this->actingAs($this->userNotAdmin);
        $this->get($this->url)->assertOk();
    }

    public function test_an_admin_can_view_a_period(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url.'/'.$this->period->id)->assertOk();
    }

    public function test_an_authorized_user_can_view_a_period(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'viewPeriod')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url.'/'.$this->period->id)->assertForbidden();

        RoleTask::create(['task_id' => 'viewPeriod', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url.'/'.$this->period->id)->assertOK();
    }

    public function test_an_admin_can_create_a_period(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url . '/create')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_create_a_period(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'createPeriod')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url . '/create')->assertForbidden();
        $this->post($this->url)->assertForbidden();

        RoleTask::create(['task_id' => 'createPeriod', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url. '/create')->assertOK();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_admin_can_edit_a_period(): void
    {
        $this->actingAs($this->admin);
        $this->get($this->url . '/'.$this->period->id.'/edit')->assertOk();
        $this->post($this->url)->assertInvalid();
    }

    public function test_an_authorized_user_can_edit_a_period(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'updatePeriod')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->get($this->url . '/'. $this->period->id.'/edit')->assertForbidden();
        $this->put($this->url . '/'. $this->period->id)->assertForbidden();

        RoleTask::create(['task_id' => 'updatePeriod', 'role_id' => $this->userNotAdmin->role_id]);
        $this->get($this->url. '/'. $this->period->id.'/edit')->assertOK();
        $this->put($this->url. '/'. $this->period->id)->assertInvalid();
    }

    public function test_an_admin_can_delete_a_period(): void
    {
        $this->actingAs($this->admin);
        $this->delete($this->url . '/'. $this->period->id)->assertRedirect('periods');
    }

    public function test_an_authorized_user_can_delete_a_period(): void
    {
        $this->actingAs($this->userNotAdmin);

        RoleTask::where('task_id', 'deletePeriod')->where('role_id', $this->userNotAdmin->role_id)->delete();
        $this->delete($this->url . '/'. $this->period->id)->assertForbidden();

        RoleTask::create(['task_id' => 'deletePeriod', 'role_id' => $this->userNotAdmin->role_id]);
        $this->delete($this->url . '/'. $this->period->id)->assertRedirect('periods');
    }
}
