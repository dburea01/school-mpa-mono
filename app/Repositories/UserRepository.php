<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
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
        $query = User::orderBy('name');

        $query->when(isset($request['name']), function ($q) use ($request) {
            return $q->where('name', 'like', '%'.$request['name'].'%');
        });

        $query->when(isset($request['email']), function ($q) use ($request) {
            return $q->where('email', 'like', '%'.$request['email'].'%');
        });

        $query->when(isset($request['provider']), function ($q) use ($request) {
            return $q->where('provider', $request['provider']);
        });

        $query->when(isset($request['login_status_id']), function ($q) use ($request) {
            return $q->where('login_status_id', $request['login_status_id']);
        });

        return $query->paginate(10);
    }

    /** @param array<string,string> $data */
    public function insert(array $data, string $login_status_id): User
    {
        $user = new User();
        $user->fill($data);
        $user->password = Hash::make($data['password']);
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
}
