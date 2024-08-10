<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Repositories\AssignmentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public AssignmentRepository $assignmentRepository;

    public RoleRepository $roleRepository;

    public SubjectRepository $subjectRepository;

    public function __construct(AssignmentRepository $assignmentRepository, RoleRepository $roleRepository, SubjectRepository $subjectRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
        $this->roleRepository = $roleRepository;
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Classroom $classroom)
    {
        $this->authorize('viewAny', Assignment::class);

        $roleId = $request->query('role_id', '');

        $assignments = $this->assignmentRepository->index(
            $classroom->id,
            $roleId
        );

        // dd($assignments);
        // dd($this->roleRepository->index(true));

        return view('assignments.assignments', [
            'classroom' => $classroom,
            'assignments' => $assignments,
            'role_id' => $roleId,
            // 'roles' => $this->roleRepository->index(true),
            // 'subjects' => $this->subjectRepository->all()

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
