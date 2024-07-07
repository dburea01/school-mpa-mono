<?php

namespace App\View\Components;

use App\Models\LoginStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectLoginStatus extends Component
{
    public Collection $loginStatuses;

    public function __construct(
        public string $value,
        public string $name,
        public string $id
    ) {
        $this->loginStatuses = LoginStatus::orderBy('position')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-login-status');
    }
}
