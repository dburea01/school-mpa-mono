<?php

namespace App\View\Components;

use App\Models\Role;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectRole extends Component
{
    public Collection $roles;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $value,
        public string $name,
        public string $id
    ) {
        $this->roles = Role::all()->sortBy('name');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-role');
    }
}
