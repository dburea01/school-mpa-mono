<?php

namespace App\View\Components;

use App\Repositories\PeriodRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectPeriod extends Component
{
    public Collection $periods;

    public string $name;

    public string $id;

    public bool $required = false;

    public ?string $value;

    public ?string $placeholder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $id, bool $required, ?string $value = null, ?string $placeholder = null)
    {
        $periodRepository = new PeriodRepository();
        $this->periods = $periodRepository->all();
        $this->name = $name;
        $this->id = $id;
        $this->required = $required;
        $this->value = $value;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select-period');
    }
}
