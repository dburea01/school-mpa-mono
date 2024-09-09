<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Group;
use App\Models\Period;
use App\Models\Result;
use App\Models\RoleTask;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Work;
use App\Models\WorkStatus;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyResultByUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $userNotAdmin;

    private User $studentA, $studentB, $parentA, $parentB;
    private Group $groupA, $groupB;

    private Period $period;

    private Work $work;

    private Result $result;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 'ADMIN']);
        $this->userNotAdmin = User::factory()->create(['role_id' => 'TEACHER']);
        
        $this->groupA = Group::factory()->create();
        $this->parentA = User::factory()->create(['role_id' => 'PARENT']);
        $this->studentA = User::factory()->create(['role_id' => 'STUDENT']);

        $this->groupB = Group::factory()->create();
        $this->parentB = User::factory()->create(['role_id' => 'PARENT']);
        $this->studentB = User::factory()->create(['role_id' => 'STUDENT']);

        UserGroup::insert([
            'group_id' => $this->groupA->id,
            'user_id' => $this->studentA->id
        ]);
        UserGroup::insert([
            'group_id' => $this->groupA->id,
            'user_id' => $this->parentA->id
        ]);
        UserGroup::insert([
            'group_id' => $this->groupB->id,
            'user_id' => $this->studentB->id
        ]);
        UserGroup::insert([
            'group_id' => $this->groupB->id,
            'user_id' => $this->parentB->id
        ]);

        
        $this->period = Period::factory()->create();
        $this->work = $this->buildWork($this->period);
        $this->result = Result::factory()->create([
            'work_id' => $this->work->id,
            'user_id' => $this->student->id
        ]);
        $this->url = '/works/' . $this->work->id . '/results';
    }

    public function test_the_admin_can_see_the_results_of_any_user(): void
    {
        $this->get('users/'.$this->studentA->id.'/results')->assertOK();
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
