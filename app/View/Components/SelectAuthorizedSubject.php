<?php

namespace App\View\Components;

use App\Models\Period;
use App\Models\User;
use App\Repositories\AssignmentRepository;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectAuthorizedSubject extends Component
{
    public Collection $subjects;

    public string $name;

    public Period $period;

    public User $user;

    public string $id;

    public bool $required = false;

    public ?string $value;

    public ?string $placeholder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Period $period, User $user, string $name, string $id, bool $required, ?string $value = null, ?string $placeholder = null)
    {
        // $subjectRepository = new SubjectRepository();
        // $this->subjects = $subjectRepository->all();
        $assignmentRepository = new AssignmentRepository();
        $this->subjects = $assignmentRepository->getAuthorizedSubjects($user, $period);

        $this->name = $name;
        $this->id = $id;
        $this->required = $required;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->user = $user;
        $this->period = $period;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select-subject');
    }
}
