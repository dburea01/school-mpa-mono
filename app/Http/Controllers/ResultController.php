<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResultRequest;
use App\Models\Appreciation;
use App\Models\Result;
use App\Models\User;
use App\Models\Work;
use App\Repositories\AppreciationRepository;
use App\Repositories\ResultRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    use AuthorizesRequests;

    public ResultRepository $resultRepository;

    public AppreciationRepository $appreciationRepository;

    public function __construct(ResultRepository $resultRepository, AppreciationRepository $appreciationRepository)
    {
        $this->resultRepository = $resultRepository;
        $this->appreciationRepository = $appreciationRepository;
    }

    public function index(Work $work): View
    {
        $this->authorize('viewAny', Result::class);

        $usersWithResult = $this->resultRepository->getUsersWithResults($work);
        // dd($usersWithResult);
        $appreciations = $this->appreciationRepository->all();

        return view('results.results', [
            'work' => $work,
            'usersWithResult' => $usersWithResult,
            'appreciations' => $appreciations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Work $work, StoreResultRequest $request): RedirectResponse
    {

        /** @var \App\Models\User $user */
        $user = User::find($request->user_id);
        // see the authorizations in the form request
        try {
            DB::beginTransaction();
            // delete the result of this user, work, and then recreate it
            $this->resultRepository->deleteResultOneUserOneWork($work, $user);
            $this->resultRepository->insert($work, $request->all());

            DB::commit();

            return redirect("works/$work->id/results")->with('success', "Résultat sauvegardé pour $user->full_name");
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', $th->getMessage());
        }
    }

    public function resultByUser(School $school, User $user, Request $request): View
    {
        $this->authorize('viewAnyStudentResult', [Result::class, $school, $user]);

        return view('results.results_by_user', [
            'school' => $school,
            'period' => $this->periodRepository->getCurrentPeriod($school),
            'user' => $user,
            'results' => $this->resultRepository->getResultsByUser($school, $user, $request->all()),
            'search' => $request->query('search', ''),
            'subject_id' => $request->query('subject_id', ''),
            'classroom_id' => $request->query('classroom_id', ''),
            'work_type_id' => $request->query('work_type_id', ''),
        ]);
    }
}
