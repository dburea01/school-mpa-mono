<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Requests\ViewAssignmentRequest;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Repositories\AssignmentRepository;
use App\Repositories\PeriodRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public AssignmentRepository $assignmentRepository;

    public PeriodRepository $periodRepository;

    public function __construct(AssignmentRepository $assignmentRepository, PeriodRepository $periodRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
        $this->periodRepository = $periodRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ViewAssignmentRequest $request): View
    {
        // get the current period
        $currentPeriod = $this->periodRepository->getCurrentPeriod();
        abort_if(!$currentPeriod, 404, 'Pas de période définie.');

        /** @var string $roleId */
        $roleId = $request->query('role_id', '');
        /** @var string $classroomId */
        $classroomId = $request->query('classroom_id');
        if ($classroomId == '') {
            $classroom = Classroom::where('period_id', $currentPeriod->id)->orderBy('short_name')->first();
            abort_if(!$classroom, 404, 'Pas de classe pour cette période.');
            $classroomId = $classroom->id;
        }
        $assignments = $this->assignmentRepository->index(
            $classroomId,
            $roleId
        );

        return view('assignments.assignments', [
            'classroom' => Classroom::find($classroomId),
            'assignments' => $assignments,
            'role_id' => $roleId,
            'period' => $currentPeriod,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
