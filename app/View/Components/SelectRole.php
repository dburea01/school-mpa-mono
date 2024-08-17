<?php

namespace App\View\Components;

use App\Repositories\RoleRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectRole extends Component
{
    public Collection $roles;

    public string $value;

    public string $name;

    public string $id;

    public ?bool $isAssignable;

    /**
     * Create a new component instance.
     */
    public function __construct(string $value, string $name, string $id, ?bool $isAssignable = null)
    {

        $this->value = $value;
        $this->name = $name;
        $this->id = $id;
        $this->isAssignable = $isAssignable;

        $roleRepository = new RoleRepository();
        $this->roles = $roleRepository->index($isAssignable);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-role');
    }
}
