<?php

namespace App\View\Components;

use App\Models\Country;
use App\Models\Subject;
use App\Repositories\SubjectRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectSubject extends Component
{
    public Collection $subjects;

    public string $name;

    public string $id;

    public bool $required = false;

    public ?string $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $id, bool $required, ?string $value = null)
    {
        $subjectRepository = new SubjectRepository();
        $this->subjects = $subjectRepository->all();
        $this->name = $name;
        $this->id = $id;
        $this->required = $required;
        $this->value = $value;
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
