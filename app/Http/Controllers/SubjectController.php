<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Models\Subject;
use App\Repositories\SubjectRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class SubjectController extends Controller
{
    use AuthorizesRequests;

    public SubjectRepository $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Subject::class);

        return view('subjects.subjects', [
            'subjects' => $this->subjectRepository->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Subject::class);
        $subject = new Subject();
        $subject->is_active = true;

        return view('subjects.subject-form', [
            'subject' => $subject,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $subject = $this->subjectRepository->insert($request->all());

            return redirect()->route('subjects.index')->with('success', "Matière $subject->name créée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): void
    {
        $this->authorize('view', $subject);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View
    {
        $this->authorize('update', [Subject::class, $subject]);

        return view('subjects.subject-form', [
            'subject' => $subject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubjectRequest $request, Subject $subject): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $subjectUpdated = $this->subjectRepository->update($subject, $request->all());

            return redirect()->route('subjects.index')->with('success', "Matière $subjectUpdated->name modifiée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        $this->authorize('delete', $subject);
        try {
            $this->subjectRepository->delete($subject);

            return redirect()->route('subjects.index')->with('success', "Matière $subject->name supprimée");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
