<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassroomRequest;
use App\Models\Classroom;
use App\Models\Period;
use App\Repositories\ClassroomRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class ClassroomController extends Controller
{
    use AuthorizesRequests;

    private ClassroomRepository $classroomRepository;

    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Period $period): View
    {
        $this->authorize('viewAny', Classroom::class);

        return view('classrooms.classrooms', [
            'classrooms' => $this->classroomRepository->index($period),
            'period' => $period,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Period $period): View
    {
        $this->authorize('create', Classroom::class);
        $classroom = new Classroom();

        return view('classrooms.classroom-form', [
            'classroom' => $classroom,
            'period' => $period,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Period $period, StoreClassroomRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $classroom = $this->classroomRepository->insert($period, $request->all());

            return redirect()->route('classrooms.index', ['period' => $period])
                ->with('success', "Classe $classroom->short_name créée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom): void
    {
        $this->authorize('view', $classroom);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Period $period, Classroom $classroom): View
    {
        $this->authorize('update', $classroom);

        return view('classrooms.classroom-form', [
            'period' => $period,
            'classroom' => $classroom,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClassroomRequest $request, Period $period, Classroom $classroom): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $classroomUpdated = $this->classroomRepository->update($classroom, $request->all());

            return redirect()->route('classrooms.index', ['period' => $period])
                ->with('success', "Classe $classroomUpdated->short_name modifiée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period, Classroom $classroom): RedirectResponse
    {
        $this->authorize('delete', $classroom);
        try {
            $this->classroomRepository->delete($classroom);

            return redirect()->route('classrooms.index', ['period' => $period])
                ->with('success', "Classe $classroom->short_name supprimée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
