<div class="card card-shadow">
    <div class="card-header text-center">
        Résumé
    </div>

    <div class="card-body">

        <strong>Titre : </strong>{{ $work->title }}<br>

        <strong>Classe : </strong>{{ $work->classroom->short_name }}<br>
        <strong>Matière : </strong>{{ $work->subject->name }}<br>
        <strong>Type : </strong>{{ $work->workType->name }}<br>

        <hr />
        <strong>Moyenne : </strong>{{ $average }}<br>
        <strong>Note mini : </strong>{{ $minimum }}<br>
        <strong>Note maxi : </strong>{{ $maximum }}<br>

        <hr />
        <strong>Nombre élèves à corriger : </strong>{{ $quantityStudents }}<br>
        <strong>Nombre absences : </strong>{{ $quantityStudentsIsAbsent }}<br>
        <strong>Notes attribuées : </strong>{{ $quantityResultsNoted }}<br>

        @php $progressionNotation = 100 * $quantityResultsNoted / ($quantityStudents - $quantityStudentsIsAbsent) @endphp

        <div class="progress mt-3" role="progressbar" aria-label="Success striped" aria-valuenow="{{ $progressionNotation }}"
            aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar bg-success" style="width: {{ $progressionNotation }}%">
                {{ round($progressionNotation) }}%
            </div>
        </div>
    </div>
</div>