<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkTypeRequest;
use App\Models\WorkType;
use App\Repositories\WorkTypeRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkTypeController extends Controller
{
    use AuthorizesRequests;

    public WorkTypeRepository $workTypeRepository;

    public function __construct(workTypeRepository $workTypeRepository)
    {
        $this->workTypeRepository = $workTypeRepository;
    }

    public function index(): View
    {
        $this->authorize('viewAny', WorkType::class);

        return view('work-types.work_types', [
            'workTypes' => $this->workTypeRepository->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', WorkType::class);
        $workType = new WorkType();
        $workType->is_active = true;

        return view('work-types.work_type_form', [
            'workType' => $workType,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkTypeRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $workType = $this->workTypeRepository->insert($request->all());

            return redirect()->route('work-types.index')->with('success', "Type de travail $workType->name créé");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkType $workType): void
    {
        $this->authorize('view', $workType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkType $workType): View
    {
        $this->authorize('update', $workType);

        return view('work-types.work_type_form', [
            'workType' => $workType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWorkTypeRequest $request, WorkType $workType): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $workTypeUpdated = $this->workTypeRepository->update($workType, $request->all());

            return redirect()->route('work-types.index')->with('success', "Type de travail $workTypeUpdated->name modifié");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkType $workType): RedirectResponse
    {
        $this->authorize('delete', $workType);
        try {
            $this->workTypeRepository->delete($workType);

            return redirect()->route('work-types.index')->with('success', "Type de travail $workType->name supprimé");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
