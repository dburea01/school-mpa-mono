<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\StorePeriodRequest;
use App\Models\Group;
use App\Models\Period;
use App\Repositories\GroupRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    use AuthorizesRequests;

    public GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Group::class);
        $groups = $this->groupRepository->index($request->all());

        return view('groups.groups', [
            'groups' => $groups,
            'name' => $request->query('name', ''),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Group::class);
        $group = new Group();

        return view('groups.group-form', [
            'group' => $group,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $group = $this->groupRepository->insert($request->all());

            return redirect()->route('groups.index')->with('success', "Groupe $group->name créé");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group): View
    {
        $this->authorize('update', [Group::class, $group]);

        return view('groups.group-form', [
            'group' => $group,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGroupRequest $request, Group $group): RedirectResponse
    {
        // see the authorizations in the form request
        try {
            $groupUpdated = $this->groupRepository->update($group, $request->all());

            return redirect()->route('groups.index')->with('success', "Groupe $groupUpdated->name modifié");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group): RedirectResponse
    {
        $this->authorize('delete', $group);
        try {
            $this->groupRepository->delete($group);

            return redirect()->route('groups.index')->with('success', "Groupe $group->name supprimé");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
