<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Models\Period;
use App\Repositories\PeriodRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class PeriodController extends Controller
{
    use AuthorizesRequests;

    public PeriodRepository $periodRepository;

    public function __construct(PeriodRepository $periodRepository)
    {
        $this->periodRepository = $periodRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Period::class);

        return view('periods.periods', [
            'periods' => $this->periodRepository->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Period::class);
        $period = new Period();

        return view('periods.period-form', [
            'period' => $period,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePeriodRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $period = $this->periodRepository->insert($request->all());

            return redirect()->route('periods.index')->with('success', "Année scolaire $period->name créée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Period $period): View
    {
        $this->authorize('update', [Period::class, $period]);

        return view('periods.period-form', [
            'period' => $period,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePeriodRequest $request, Period $period): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $periodUpdated = $this->periodRepository->update($period, $request->all());

            return redirect()->route('periods.index')->with('success', "Année scolaire $periodUpdated->name modifiée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period): RedirectResponse
    {
        $this->authorize('delete', $period);
        try {
            $this->periodRepository->delete($period);

            return redirect()->route('periods.index')->with('success', "Année scolaire $period->name supprimée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
