<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkRequest;
use App\Http\Requests\UpdateWorkRequest;
use App\Models\Period;
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

        // dd($this->workRepository->all($currentPeriod, $request->all()));
        return view('works.works', [
            'period' => $period,
            'works' => $this->workRepository->index($period, Auth::user(), $request->all()),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work): View
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkRequest $request, Work $work): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work): RedirectResponse
    {
        //
    }
}
