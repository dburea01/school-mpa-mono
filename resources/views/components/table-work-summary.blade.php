<div class="border border-primary border-3 rounded p-1 mb-2" style="background-color: aqua">

    <h2 class="text-center">Résumé</h2>
    <strong><u>Titre :</u> </strong>{{ $work->title }}<br>
    <em>{{ $work->description }}</em><br><br>

    <strong><u>Classe :</u> </strong>{{ $work->classroom->short_name }}<br>
    <strong><u>Matière :</u> </strong>{{ $work->subject->name }}<br>
    <strong><u>Type :</u> </strong>{{ $work->workType->name }}<br>
    <strong><u>Barêmes :</u> </strong>{{ $work->note_min }} / {{ $work->note_max }} /
    {{ $work->note_increment }}<br><br>

    <h2 class="text-center">Résultats</h2>
    <strong><u>Moyenne :</u> </strong>{{ $average }}<br>
    <strong><u>Mini/Maxi :</u> </strong>{{ $minimum }} / {{ $maximum }}<br>
    <strong><u>Nombre élèves à corriger :</u> </strong>{{ $quantityStudents }}<br>
    <strong><u>Nombre absences :</u> </strong>{{ $quantityStudentsIsAbsent }}<br>
    <strong><u>Notes attribuées :</u> </strong>{{ $quantityResultsNoted }}<br>

    @php $progressionNotation = 100 * $quantityResultsNoted / ($quantityStudents - $quantityStudentsIsAbsent) @endphp

    <div class="progress" role="progressbar" aria-label="Success striped" aria-valuenow="{{ $progressionNotation }}"
        aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar bg-success" style="width: {{ $progressionNotation }}%">
            {{ round($progressionNotation) }}%
        </div>
    </div>
</div>
