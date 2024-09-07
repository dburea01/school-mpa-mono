<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyResultRequest;
use App\Http\Requests\IndexResultRequest;
use App\Http\Requests\StoreResultRequest;
use App\Http\Resources\ResultResource;
use App\Models\Result;
use App\Models\User;
use App\Models\Work;
use App\Repositories\AppreciationRepository;
use App\Repositories\ResultRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function index(Work $work, IndexResultRequest $request): View
    {
        $this->authorize('viewAny', Result::class);

        /** @var string $sort */
        $sort = $request->input('sort', 'name');
        /** @var string $direction */
        $direction = $request->input('direction', 'asc');

        $usersWithResult = $this->resultRepository->getUsersWithResults($work, $sort, $direction);

        // dd($usersWithResult);
        return view('results.results', [
            'work' => $work,
            'usersWithResult' => $usersWithResult,
            'direction' => $direction == 'asc' ? 'desc' : 'asc',
        ]);
    }

    public function create(Work $work, Request $request): View
    {
        $this->authorize('create', Result::class);
        $request->validate([
            'user_id' => 'required|uuid'
        ]);

        $result = new Result();
        $user = User::findOrFail($request->user_id);

        return view('results.result_form', [
            'work' => $work,
            'result' => $result,
            'user' => $user,
            'appreciations' => $this->appreciationRepository->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Work $work, StoreResultRequest $request): RedirectResponse
    {
        // see the authorizations in the form request

        /** @var \App\Models\User $user */
        $user = User::find($request->user_id);

        try {
            DB::beginTransaction();
            // delete the result of this user, work, and then recreate it
            $this->resultRepository->deleteResultOneUserOneWork($work, $user);
            $result = $this->resultRepository->insert($work, $request->all());

            DB::commit();

            return redirect("works/$work->id/results")->with('success', "Résultat sauvegardé pour $user->full_name");
        } catch (\Throwable $th) {
            DB::rollback();

            // return back()->with('error', $th->getMessage());
            return response()->json($th->getMessage(), 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work, Result $result): void
    {
        $this->authorize('view', $result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Work $work, Result $result, StoreResultRequest $request): RedirectResponse
    {
        // see the authorizations in the form request

        /** @var \App\Models\User $user */
        $user = User::find($request->user_id);

        try {
            $resultUpdated = $this->resultRepository->update($result, $request->all());

            return redirect("works/$work->id/results")->with('success', "Résultat sauvegardé pour $user->full_name");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work, Result $result): View
    {
        $this->authorize('update', $result);

        $user = User::findOrFail($result->user_id);
        return view('results.result_form', [
            'work' => $work,
            'result' => $result,
            'user' => $user,
            'appreciations' => $this->appreciationRepository->all()
        ]);
    }

    public function destroy(Work $work, Result $result): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($result->user_id);

        try {
            // delete the result of this user, work,
            $this->resultRepository->delete($result);

            return redirect("works/$work->id/results")->with('success', "Résultat supprimé pour $user->full_name");
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 422);
        }
    }

    public function resultByUser(User $user, Request $request): View
    {
        // $this->authorize('viewAnyStudentResult', [Result::class, $user]);

        return view('results.results_by_user', [
            // 'period' => $this->periodRepository->getCurrentPeriod($school),
            'user' => $user,
            'results' => $this->resultRepository->getResultsByUser($user, $request->all()),
            'search' => $request->query('search', ''),
            'subject_id' => $request->query('subject_id', ''),
            'classroom_id' => $request->query('classroom_id', ''),
            'work_type_id' => $request->query('work_type_id', ''),
        ]);
    }
}
