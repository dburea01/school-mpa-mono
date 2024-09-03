<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectAppreciation extends Component
{
    public Collection $appreciations;

    public ?string $value;

    public string $name;

    public ?string $onchange;

    /**
     * Create a new component instance.
     */
    public function __construct(Collection $appreciations, ?string $value, string $name, string $onchange = null)
    {
        $this->appreciations = $appreciations;
        $this->value = $value;
        $this->name = $name;
        $this->onchange = $onchange;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-appreciation');
    }
}
