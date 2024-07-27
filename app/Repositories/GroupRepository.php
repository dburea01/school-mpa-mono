<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Period;
use Illuminate\Database\Eloquent\Collection;

class GroupRepository
{
    /**
     * @param  array<string>  $request
     * @return LengthAwarePaginator<Group>
     */
    public function index(array $request)
    {
        $query = Group::with('users')->orderBy('name');

        $query->when(isset($request['name']), function ($q) use ($request) {
            return $q->where('name', 'like' , '%'.$request['name'].'%');
        });

        return $query->paginate();
    }

    /** @param array<string,string> $data */
    public function insert(array $data): Group
    {
        $group = new Group();
        $group->fill($data);
        $group->save();

        return $group;
    }

    /** @param array<string,string> $data */
    public function update(Group $group, array $data): Group
    {
        $group->fill($data);
        $group->save();

        return $group;
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }
}
