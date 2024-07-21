<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Repositories\PeriodRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
    public function index()
    {
        $this->authorize('viewAny', Period::class);

        return view('periods.periods', [
            'periods' => $this->periodRepository->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function store(StorePeriodRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Period $period)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Period $period)
    {
        $this->authorize('update', [Period::class, $period]);

        return view('periods.period-form', [
            'period' => $period,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePeriodRequest $request, Period $period)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period)
    {
        //
    }
}
