<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkRequest;
use App\Models\Period;
use App\Models\User;
use App\Models\Work;
use App\Repositories\WorkRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    use AuthorizesRequests;

    public WorkRepository $workRepository;

    public function __construct(WorkRepository $workRepository)
    {
        $this->workRepository = $workRepository;
    }

    public function index(Period $period, Request $request): View
    {
        $this->authorize('viewAny', Work::class);

        /** @var User $user */
        $user = Auth::user();

        return view('works.works', [
            'period' => $period,
            'works' => $this->workRepository->index($period, $user, $request->all()),
            'title' => $request->query('title', ''),
            'subjectId' => $request->query('subject_id', ''),
            'classroomId' => $request->query('classroom_id', ''),
            'workTypeId' => $request->query('work_type_id', ''),
            'workStatusId' => $request->query('work_status_id', ''),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Period $period): View
    {
        $this->authorize('create', Work::class);

        $work = new Work();
        $work->work_status_id = 'PLANNED';
        $work->work_type_id = 1; // DNS
        $work->note_min = 0;
        $work->note_max = 20;
        $work->note_increment = 0.5;

        return view('works.work_form', [
            'period' => $period,
            'work' => $work,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Period $period, StoreWorkRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $work = $this->workRepository->insert($request->all());

            return redirect()->route('works.index', ['period' => $period])
                ->with('success', "Travail $work->title créé");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Period $period, Work $work): void
    {
        $this->authorize('view', $work);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Period $period, Work $work): View
    {
        $this->authorize('update', $work);

        return view('works.work_form', [
            'period' => $period,
            'work' => $work,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWorkRequest $request, Period $period, Work $work): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $workUpdated = $this->workRepository->update($work, $request->all());

            return redirect()->route('works.index', ['period' => $period])
                ->with('success', "Travail $workUpdated->title modifié");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period, Work $work): RedirectResponse
    {
        $this->authorize('delete', $work);
        try {
            $this->workRepository->delete($work);

            return redirect()->route('works.index', [
                'period' => $period,
            ])
                ->with('success', "Travail $work->title supprimé");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
