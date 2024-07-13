<?php

namespace App\View\Components;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectCountry extends Component
{
    public Collection $countries;

    public string $name;

    public string $id;

    public bool $required = false;

    public string|null  $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $id, bool $required, string $value = null)
    {
        $this->countries = Country::orderBy('position')->get();
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
        return view('components.select-country');
    }
}
