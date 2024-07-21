<?php

namespace App\View\Components;

use App\Models\Period;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DisplayCurrentPeriod extends Component
{
    public ?Period $currentPeriod;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentPeriod = Period::where('is_current', 1)->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.display-current-period');
    }
}
