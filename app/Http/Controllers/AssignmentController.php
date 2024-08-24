<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\User;
use App\Repositories\AssignmentRepository;
use App\Repositories\PeriodRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
    public function index(Classroom $classroom, Request $request): View
    {
        $this->authorize('viewAny', Assignment::class);

        /** @var string $roleId */
        $roleId = $request->query('role_id', '');

        $assignments = $this->assignmentRepository->index(
            $classroom->id,
            $roleId
        );

        return view('assignments.assignments', [
            'classroom' => $classroom,
            'assignments' => $assignments,
            'role_id' => $roleId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Classroom $classroom): View
    {
        $this->authorize('create', Assignment::class);

        $assignment = new Assignment();
        $assignment->classroom_id = $classroom->id;

        return view('assignments.assignment-form', [
            'assignment' => $assignment,
            'classroom' => $classroom,
            'user' => new User(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Classroom $classroom, StoreAssignmentRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $assignment = $this->assignmentRepository->insert($classroom, $request->all());

            /** @var User $assignedUser */
            $assignedUser = User::find($assignment->user_id);

            return redirect()
                ->route('assignments.index', ['classroom' => $classroom, 'role_id' => $assignedUser->role_id])
                ->with('success', "Affectation de $assignedUser->full_name créée dans classe $classroom->short_name");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Assignment $assignment): void
    {
        $this->authorize('view', $assignment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom, Assignment $assignment): View
    {
        $this->authorize('update', [Assignment::class, $assignment]);

        return view('assignments.assignment-form', [
            'assignment' => $assignment,
            'classroom' => Classroom::find($assignment->classroom_id),
            'user' => User::find($assignment->user_id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Classroom $classroom, Assignment $assignment, StoreAssignmentRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $this->assignmentRepository->update($assignment, $request->all());

            /** @var User $assignedUser */
            $assignedUser = User::find($assignment->user_id);

            return redirect()
                ->route('assignments.index', ['classroom' => $classroom, 'role_id' => $assignedUser->role_id])
                ->with('success', "Affectation de $assignedUser->full_name modifiée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, Assignment $assignment): RedirectResponse
    {
        $this->authorize('delete', $assignment);

        // find the user name for the message sent to the redirection
        /** @var User $user */
        $user = User::find($assignment->user_id);

        try {
            $this->assignmentRepository->delete($assignment);

            return redirect()
                ->route('assignments.index', ['classroom' => $classroom, 'role_id' => $user->role_id])
                ->with('success', "Utilisateur $user->full_name retiré de la classe $classroom->short_name");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Utilisateur non retiré');
        }
    }
}
