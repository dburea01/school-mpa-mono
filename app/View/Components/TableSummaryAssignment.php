<?php

namespace App\View\Components;

use App\Models\Classroom;
use App\Repositories\AssignmentRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TableSummaryAssignment extends Component
{
    public Classroom $classroom;

    public Collection $repartitionByRole;

    public Collection $repartitionByGender;

    public function __construct(Classroom $classroom)
    {
        $this->classroom = $classroom;

        $assignmentRepository = new AssignmentRepository();
        $this->repartitionByRole = $assignmentRepository->repartition_assignment_by_role($this->classroom);
        $this->repartitionByGender = $assignmentRepository->repartition_assignment_by_gender($this->classroom);

        // dd($this->repartitionByGender);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-summary-assignment');
    }
}
