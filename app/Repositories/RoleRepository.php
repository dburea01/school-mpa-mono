<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function index(?bool $isAssignable): Collection
    {
        $query = Role::where('is_active', true)->orderBy('name');

        if (isset($isAssignable)) {
            $query->where('is_assignable', $isAssignable);
        }

        return $query->get();
    }
}
