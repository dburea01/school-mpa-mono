<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserGroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    use AuthorizesRequests;

    private UserGroupRepository $userGroupRepository;

    private UserRepository $userRepository;

    public function __construct(UserGroupRepository $userGroupRepository, UserRepository $userRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Group $group, Request $request): View
    {
        
        $this->authorize('viewAny', UserGroup::class);

        $groupWithUsers = $this->userGroupRepository->allUsersOfAGroup($group);
        $usersFiltered = $request->has('name') && $request->name !== '' ?
            $this->userRepository->index($request->all()) : [];

        return view('user.users-of-a-group', [
            'group' => $group,
            'groupWithUsers' => $groupWithUsers,
            'usersFiltered' => $usersFiltered,
            'name' => $request->query('name', ''),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Group $group, StoreUserGroupRequest $request): RedirectResponse
    {
        /** @var string $userId */
        $userId = $request->input('user_id');

        /** @var string $name */
        $name = $request->input('name');

        try {
            $this->userGroupRepository->insert($group, $userId);

            /** @var User $user */
            $user = User::find($request->user_id);


            return redirect("/groups/$group->id/users?name=$name")->with('success', "$user->full_name ajouté au groupe $group->name");
        } catch (\Throwable $th) {
            return back()->with('error', 'an error occured.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, User $user, Request $request): RedirectResponse
    {
        $this->authorize('delete', UserGroup::class);

        /** @var string $name */
        $name = $request->input('name');

        try {
            $this->userGroupRepository->delete($group, $user);

            return redirect("/groups/$group->id/users?name=$name")
                ->with('success', "$user->full_name a été supprimé du groupe $group->name");
        } catch (\Throwable $th) {
            return back()->with('error', 'an error occured.');
        }
    }
}
