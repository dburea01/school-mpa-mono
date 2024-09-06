<?php

namespace App\View\Components;

use App\Models\Work;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TableWorkSummary extends Component
{
    public string $average;

    public string $maximum;

    public string $minimum;

    public int $quantityResultsNoted;

    public int $quantityStudents;

    public int $quantityStudentsIsAbsent;

    public Collection $resultsNotNotedYet;

    public Collection $resultsNoted;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Work $work,
        public Collection $usersWithResult,
    ) {
        $this->resultsNotNotedYet = $usersWithResult->filter(function ($user) {
            /** @phpstan-ignore-next-line */
            return $user->result == null;
        });

        $this->resultsNoted = $usersWithResult->filter(function ($user) {
            /** @phpstan-ignore-next-line */
            return $user->result != null && $user->result->note != null && $user->result->is_absent == false;
        });

        $this->quantityStudents = $usersWithResult->count();
        $this->quantityStudentsIsAbsent = $usersWithResult->filter(function ($user) {
            /** @phpstan-ignore-next-line */
            return $user->result != null && $user->result->is_absent;
        })->count();

        $this->quantityResultsNoted = $this->resultsNoted->count();

        /** @phpstan-ignore-next-line */
        $this->average = $this->resultsNoted->count() != 0 ? number_format(round($this->resultsNoted->pluck('result')->average('note'), 2), 2) : '';
        /** @phpstan-ignore-next-line */
        $this->minimum = $this->resultsNoted->count() != 0 ? $this->resultsNoted->pluck('result')->min('note') : '';
        /** @phpstan-ignore-next-line */
        $this->maximum = $this->resultsNoted->count() != 0 ? $this->resultsNoted->pluck('result')->max('note') : '';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-work-summary');
    }
}
