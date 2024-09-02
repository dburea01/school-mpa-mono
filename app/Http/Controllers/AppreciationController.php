<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppreciationRequest;
use App\Models\Appreciation;
use App\Repositories\AppreciationRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class AppreciationController extends Controller
{
    use AuthorizesRequests;

    public AppreciationRepository $appreciationRepository;

    public function __construct(AppreciationRepository $appreciationRepository)
    {
        $this->appreciationRepository = $appreciationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Appreciation::class);

        return view('appreciations.appreciations', [
            'appreciations' => $this->appreciationRepository->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Appreciation::class);
        $appreciation = new Appreciation();
        $appreciation->is_active = true;
        $appreciation->position = 0;

        return view('appreciations.appreciation_form', [
            'appreciation' => $appreciation,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppreciationRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $appreciation = $this->appreciationRepository->insert($request->all());

            return redirect()->route('appreciations.index')->with('success', "Appréciation $appreciation->name créée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appreciation $appreciation): void
    {
        $this->authorize('view', $appreciation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appreciation $appreciation): View
    {
        $this->authorize('update', $appreciation);

        return view('appreciations.appreciation_form', [
            'appreciation' => $appreciation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAppreciationRequest $request, Appreciation $appreciation): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $appreciationUpdated = $this->appreciationRepository->update($appreciation, $request->all());

            return redirect()->route('appreciations.index')->with('success', "Appréciation $appreciationUpdated->name modifiée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appreciation $appreciation): RedirectResponse
    {
        $this->authorize('delete', $appreciation);
        try {
            $this->appreciationRepository->delete($appreciation);

            return redirect()->route('appreciations.index')->with('success', "Appréciation $appreciation->name supprimée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
