<?php

namespace App\View\Components;

use App\Models\Period;
use App\Repositories\ClassroomRepository;
use App\Repositories\RoleRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectClassroomOfPeriod extends Component
{
    public Collection $classrooms;
    public Period $period;
    public string $value;
    public string $name;
    public string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(Period $period, $value, $name, $id)
    {
        $this->period = $period;
        $this->value = $value;
        $this->name = $name;
        $this->id = $id;

        $classroomRepository = new ClassroomRepository();
        $this->classrooms = $classroomRepository->index($period);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-classroom-of-period');
    }
}
