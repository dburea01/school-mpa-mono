<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\ViewAssignmentRequest;
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
    public function index(ViewAssignmentRequest $request): View
    {
        // get the current period
        $currentPeriod = $this->periodRepository->getCurrentPeriod();
        abort_if(! $currentPeriod, 404, 'Pas de période définie.');

        /** @var string $roleId */
        $roleId = $request->query('role_id', '');
        /** @var string $classroomId */
        $classroomId = $request->query('classroom_id');
        if ($classroomId == '') {
            $classroom = Classroom::where('period_id', $currentPeriod->id)->orderBy('short_name')->first();
            abort_if(! $classroom, 404, 'Pas de classe pour cette période.');
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
    public function create(Request $request): View
    {
        $this->authorize('create', Assignment::class);

        abort_if(! $request->has('classroom_id'), 404, 'Classe obligatoire pour créer une affectation');
        $classroom = Classroom::findOrFail($request->classroom_id);

        $assignment = new Assignment();
        /** @phpstan-ignore-next-line */
        $assignment->classroom_id = $request->input('classroom_id');

        return view('assignments.assignment-form', [
            'assignment' => $assignment,
            'classroom' => $classroom,
            'user' => new User(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $assignment = $this->assignmentRepository->insert($request->all());

            /** @var User $assignedUser */
            $assignedUser = User::find($assignment->user_id);
            /** @var Classroom $classroom */
            $classroom = Classroom::find($assignment->classroom_id);

            return redirect()
                ->route('assignments.index', ['classroom_id' => $assignment->classroom_id])
                ->with('success', "Affectation de $assignedUser->full_name créée dans classe $classroom->short_name");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment): View
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
    public function update(StoreAssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $this->assignmentRepository->update($assignment, $request->all());

            /** @var User $assignedUser */
            $assignedUser = User::find($assignment->user_id);

            return redirect()
                ->route('assignments.index', ['classroom_id' => $assignment->classroom_id])
                ->with('success', "Affectation de $assignedUser->full_name modifiée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment, Request $request): RedirectResponse
    {
        $this->authorize('delete', $assignment);

        // find the user name and the classroom name, for the message sent to the redirection
        /** @var User $user */
        $user = User::find($assignment->user_id);
        /** @var Classroom $classroom */
        $classroom = Classroom::find($assignment->classroom_id);

        try {
            $this->assignmentRepository->delete($assignment);

            return redirect()
                ->route('assignments.index', ['classroom_id' => $request->input('classroom_id')])
                ->with('success', "Utilisateur $user->full_name retiré de la classe $classroom->short_name");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Utilisateur non retiré');
        }
    }
}
