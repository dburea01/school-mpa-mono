<?php

namespace App\View\Components;

use App\Models\Civility;
use App\Models\WorkType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectWorkType extends Component
{
    public Collection $workTypes;

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

        $this->workTypes = WorkType::orderBy('name')->where('is_active', true)->get();
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
       

        return view('components.select-work-type');
    }
}
