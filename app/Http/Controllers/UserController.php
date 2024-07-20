<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResourceBasic;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    use AuthorizesRequests;

    public UserRepository $userRepository;
    // public PhotoService $photoService;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        // $this->photoService = $photoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);
        $users = $this->userRepository->index($request->all());

        return view('user.users', [
            'users' => $users,
            'name' => $request->query('name', ''),
            'email' => $request->query('email', ''),
            'login_status_id' => $request->query('login_status_id', ''),
            'role_id' => $request->query('role_id', ''),
            'mode' => $request->query('mode', 'table'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', User::class);
        $user = new User();
        $user->role_id = 'STUDENT';
        $user->login_status_id = 'CREATED';

        return view('user.user-form', [
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // see the form request for the authorizations

        // check if the user to create is a potential duplicated user.
        if (! $request->has('check_duplicated_user_done')) {

            /** @var string $lastName */
            $lastName = $request->last_name;
            /** @var string $firstName */
            $firstName = $request->first_name;

            $existingUsers = $this->userRepository->getDuplicatedUser($lastName, $firstName, null);

            if ($existingUsers->count() !== 0) {
                $userToCreate = new User();
                $userToCreate->fill($request->all());
                $roleUserToCreate = Role::find($request->role_id);

                session([
                    'existingUsers' => $existingUsers,
                    'userToCreate' => $userToCreate,
                    'roleUserToCreate' => $roleUserToCreate,
                ]);

                return redirect('/potential-duplicated-user');
            }
        }

        try {
            $user = $this->userRepository->insert($request->all(), 'CREATED');

            return redirect("/users?name=$user->last_name")
                ->with('success', "Utilisateur $user->full_name créé");
        } catch (\Throwable $th) {
            return back()->with('error', 'Utilisateur non créé'.$th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user):void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $this->authorize('update', [User::class, $user]);

        return view('user.user-form', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        // see the form request for the authorizations
        try {
            $userUpdated = $this->userRepository->update($user, $request->all());

            return redirect("/users?name=$user->last_name")
                ->with('success', "Utilisateur $userUpdated->full_name modifié");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        try {
            $this->userRepository->delete($user);

            return redirect()->route('users.index')->with('success', "Utilisateur $user->full_name supprimé");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Utilisateur $user->full_name non supprimé");
        }
    }

    public function findDuplicatedUser(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userRepository->getDuplicatedUser($request->last_name, $request->first_name, $request->id);

        return UserResourceBasic::collection($users);
    }

    public function potentialDuplicatedUser(): View
    {
        return view('user.potential-duplicated-user', [
            'userToCreate' => session('userToCreate'),
            'existingUsers' => session('existingUsers'),
        ]);
    }
}
