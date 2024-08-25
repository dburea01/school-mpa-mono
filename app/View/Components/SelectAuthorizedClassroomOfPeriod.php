<?php

namespace App\View\Components;

use App\Models\Period;
use App\Models\User;
use App\Repositories\AssignmentRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectAuthorizedClassroomOfPeriod extends Component
{
    public Collection $classrooms;

    public Period $period;

    public User $user;

    public ?string $value;

    public ?bool $required;

    public string $placeholder;

    public string $name;

    public string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(Period $period, User $user, ?string $value, bool $required, string $placeholder, string $name, string $id)
    {
        $this->period = $period;
        $this->user = $user;
        $this->value = $value;
        $this->name = $name;
        $this->id = $id;
        $this->required = $required;
        $this->placeholder = $placeholder;

        // $classroomRepository = new ClassroomRepository();
        // $this->classrooms = $classroomRepository->index($period);
        $assignmentRepository = new AssignmentRepository();
        $this->classrooms = $assignmentRepository->getAuthorizedClassrooms($user, $period);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-classroom-of-period');
    }
}
