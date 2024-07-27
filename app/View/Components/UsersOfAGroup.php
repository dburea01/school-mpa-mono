<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class UsersOfAGroup extends Component
{
    public Collection $users;

    /**
     * Create a new component instance.
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.users-of-a-group');
    }
}
