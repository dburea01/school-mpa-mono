<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Http\Requests\StoreWorkRequest;
use App\Http\Requests\UpdateWorkRequest;
use App\Repositories\PeriodRepository;
use App\Repositories\WorkRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    use AuthorizesRequests;

    public WorkRepository $workRepository;
    public PeriodRepository $periodRepository;

    public function __construct(WorkRepository $workRepository, PeriodRepository $periodRepository)
    {
        $this->workRepository = $workRepository;
        $this->periodRepository = $periodRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Work::class);

        $currentPeriod = $this->periodRepository->getCurrentPeriod();
        // dd($this->workRepository->all($currentPeriod, $request->all()));
        return view('works.works', [
            'period' => $currentPeriod,
            'works' => $this->workRepository->all($currentPeriod, $request->all()),
            'title' => $request->query('title', ''),
            'subjectId' => $request->query('subject_id', ''),
            'classroomId' => $request->query('classroom_id', ''),
            'workTypeId' => $request->query('work_type_id', ''),
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
    public function store(StoreWorkRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkRequest $request, Work $work)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        //
    }
}
