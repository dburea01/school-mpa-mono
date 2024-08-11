<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Requests\ViewAssignmentRequest;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Repositories\AssignmentRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Repositories\PeriodRepository;

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
    public function index(ViewAssignmentRequest $request)
    {
        // get the current period
        $currentPeriod = $this->periodRepository->getCurrentPeriod();
        abort_if(!$currentPeriod, 404, 'Pas de période définie.');

        $roleId = $request->query('role_id', '');
        $classroomId = $request->query('classroom_id', '');

        $assignments = $this->assignmentRepository->index(
            $classroomId,
            $roleId
        );

        return view('assignments.assignments', [
            'classroomId' => $classroomId,
            'classroom' => Classroom::find($classroomId),
            'assignments' => $assignments,
            'role_id' => $roleId,
            'period' => $currentPeriod
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
