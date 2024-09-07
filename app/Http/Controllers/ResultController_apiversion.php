<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyResultRequest;
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
    public function store(Work $work, StoreResultRequest $request): JsonResponse|ResultResource
    {

        /** @var \App\Models\User $user */
        $user = User::find($request->user_id);
        // see the authorizations in the form request
        try {
            DB::beginTransaction();
            // delete the result of this user, work, and then recreate it
            $this->resultRepository->deleteResultOneUserOneWork($work, $user);
            $result = $this->resultRepository->insert($work, $request->all());

            DB::commit();

            // return redirect("works/$work->id/results")->with('success', "Résultat sauvegardé pour $user->full_name");
            // return response()->json($result);
            return new ResultResource($result);
        } catch (\Throwable $th) {
            DB::rollback();

            // return back()->with('error', $th->getMessage());
            return response()->json($th->getMessage(), 422);
        }
    }

    public function destroy(Work $work, DestroyResultRequest $request): JsonResponse|Response
    {

        /** @var \App\Models\User $user */
        $user = User::find($request->user_id);

        try {
            // delete the result of this user, work,
            $this->resultRepository->deleteResultOneUserOneWork($work, $user);

            return response()->noContent();
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
