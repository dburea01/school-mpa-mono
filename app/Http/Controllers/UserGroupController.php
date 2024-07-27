<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use App\Http\Requests\StoreUserGroupRequest;
use App\Http\Requests\UpdateUserGroupRequest;
use App\Models\Group;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
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
    public function index(Group $group, Request $request)
    {
        // @todo : permissions
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserGroupRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserGroup $userGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserGroup $userGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserGroupRequest $request, UserGroup $userGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserGroup $userGroup)
    {
        //
    }
}
