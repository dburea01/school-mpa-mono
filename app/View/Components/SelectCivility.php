<?php

namespace App\View\Components;

use App\Models\Civility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectCivility extends Component
{
    public Collection $civilities;

    public string $type;

    public string $name;

    public string $id;

    public bool $required = false;

    public ?string $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type, string $name, string $id, bool $required, ?string $value = null)
    {
        $this->civilities = Civility::orderBy('position')->get();
        $this->type = $type;
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
        if ($this->type == 'select') {
            return view('components.select-civility');
        }

        if ($this->type == 'radio') {
            return view('components.radio-civility');
        }

        return view('components.radio-civility');
    }
}
