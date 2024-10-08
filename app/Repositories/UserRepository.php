<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    /**
     * @param  array<string>  $request
     * @return LengthAwarePaginator<User>
     */
    public function index(array $request)
    {
        $query = User::with(['role', 'civility', 'groups'])->orderBy('last_name');

        $query->when(isset($request['role_id']), function ($q) use ($request) {
            return $q->where('role_id', $request['role_id']);
        });

        $query->when(isset($request['name']), function ($q) use ($request) {
            return $q->where(function (Builder $query2) use ($request) {
                $query2->where('last_name', 'like', '%'.$request['name'].'%')
                    ->orWhere('first_name', 'like', '%'.$request['name'].'%');
            });
        });

        $query->when(isset($request['email']), function ($q) use ($request) {
            return $q->where('email', 'like', '%'.$request['email'].'%');
        });

        $query->when(isset($request['login_status_id']), function ($q) use ($request) {
            return $q->where('login_status_id', $request['login_status_id']);
        });

        return $query->paginate();
    }

    /** @param array<string,string> $data */
    public function insert(array $data, string $login_status_id): User
    {
        $user = new User();
        $user->fill($data);
        // $user->password = Hash::make($data['password']);
        $user->email_verification_code = Str::random(32);
        $user->login_status_id = $login_status_id;
        $user->save();

        return $user;
    }

    /** @param array<string,string> $data */
    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function changePassword(User $user, string $password): void
    {
        User::where('id', $user->id)
            ->update([
                'password' => Hash::make($password),
            ]);
    }

    public function changeUserState(User $user, string $login_status_id): void
    {
        $user->login_status_id = $login_status_id;
        $user->save();
    }

    public function validateUser(User $user): void
    {
        User::where('id', $user->id)
            ->update([
                'user_state_id' => 'VALIDATED',
                'email_verified_at' => now(),
            ]);
    }

    public function getDuplicatedUser(string $lastName, string $firstName, ?string $userIdToIgnore): Collection
    {

        // @todo : add more and more possibilities ....

        $query = User::with('role')
            ->where(function (Builder $query) use ($lastName, $firstName) {
                $query->where(function (builder $query2) use ($lastName, $firstName) {
                    $query2->where('last_name', strtoupper($lastName))
                        ->where('first_name', ucfirst($firstName));
                })
                    ->orWhere(function (Builder $query2) use ($lastName, $firstName) {
                        $query2->where('last_name', ucfirst($firstName))
                            ->where('first_name', strtoupper($lastName));
                    });
            });

        $query->when(isset($userIdToIgnore), function ($q) use ($userIdToIgnore) {
            return $q->where('id', '<>', $userIdToIgnore);
        });

        return $query->get();
    }

    public function getUsersByNameAndRole(string $name, ?string $roleId): Collection
    {
        $query = User::with(['role', 'civility'])->orderBy('last_name');

        $query->when(filled($roleId), function ($q) use ($roleId) {
            return $q->where('role_id', $roleId);
        });

        $query->when(filled($name), function ($q) use ($name) {
            return $q->where(function (Builder $query2) use ($name) {
                $query2->where('last_name', 'like', '%'.$name.'%')
                    ->orWhere('first_name', 'like', '%'.$name.'%');
            });
        });

        return $query->take(10)->get();
    }
}
