<?php

namespace App\View\Components;

use App\Models\WorkStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectWorkStatus extends Component
{
    public Collection $workStatuses;

    public ?string $placeholder;
    public string $name;

    public string $id;

    public bool $required = false;

    public ?string $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(?string $placeholder = null, string $name, string $id, bool $required, ?string $value = null)
    {

        $this->workStatuses = WorkStatus::orderBy('position')->get();
        $this->placeholder = $placeholder;
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


        return view('components.select-work-status');
    }
}
