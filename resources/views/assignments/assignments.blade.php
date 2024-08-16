@extends('layout')
@section('title', "Affectations")

@section('content')
@include('errors.session-values')



<div class="row">

    <div class="col-md-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header text-center">Filtres</div>
                    <div class="card-body">
                        <form class="row" action="{{ route('assignments.index', ['classroom'=>$classroom]) }}">

                            <div class="mt-1">
                                <x-select-classroom-of-period :period="$period" :value="$classroom->id" name="classroom_id" id="classroom_id" />
                                @error('classroom_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-1">
                                <x-select-role :value="$role_id" :is_assignable="true" name="role_id" id="role_id" />
                                @error('role_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header text-center">Résumé de la classe <strong><span class="text-primary">{{ $classroom->short_name }}</span></strong></div>
                    <div class="card-body">
                        <x-table-summary-assignment :classroom="$classroom" />
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-9">

        <div class="card shadow">
            <div class="card-header text-center">
                Affectation(s) - classe <strong><span class="text-primary">{{ $classroom->short_name }}</span></strong>
                @can('create', App\Models\Assignment::class)
                <a href="{{ route('assignments.create', ['classroom_id' => $classroom->id ]) }}">Créer affectation</a>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-bordered" aria-label="list of the assignments of a classroom">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Classe</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($assignments as $assignment)
                        <tr>
                            <td>
                                {{ $assignment->last_name.' '.$assignment->first_name }}

                                @if($assignment->role_id == 'STUDENT')
                                @switch($assignment->gender_id)
                                @case(1)
                                <i class="bi bi-gender-male"></i>
                                @break
                                @case(2)
                                <i class="bi bi-gender-female"></i>
                                @break
                                @endswitch
                                @endif
                            </td>
                            <td>
                                {{ $assignment->role_name }}

                                @if ($assignment->subject_id != null)
                                <span class="badge text-bg-primary">{{ $assignment->subject_name }}</span>
                                @endif
                            </td>
                            <td>{{ $assignment->classroom_short_name }}</td>
                            <td>
                                @can('delete', App\Models\Assignment::class)

                                <i class="bi bi-trash btn-delete-assignment text-danger"
                                    style="cursor: pointer;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteAssignment"
                                    data-assignment-id="{{$assignment->id}}"
                                    data-username="{{ $assignment->last_name.' '.$assignment->first_name }}"
                                    data-classroom-name="{{ $assignment->classroom_short_name }}"
                                    data-classroom-id="{{ $assignment->classroom_id }}"
                                    title="Retirer {{ $assignment->last_name.' '.$assignment->first_name }} de la classe {{ $assignment->classroom_short_name }}">
                                </i>

                                @endcan
                                @can('update', $assignment->id)
                                <a href="{{ route('assignments.edit', ['assignment' => $assignment->id ]) }}">
                                    <i class="bi bi-pencil" title="Modifier affectation de {{ $assignment->last_name.' '.$assignment->first_name }}"></i>
                                </a>
                                @endcan

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- modal to delete assignment --}}
    <div class="modal fade" id="deleteAssignment" tabindex="-1" aria-labelledby="deleteAssignmentModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAssignmentModal">Retirer <strong><span id="user-name-to-delete"></span></strong> de la classe <strong><span id="classroom-name-to-delete"></span></strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>La suppression d'une affectation est irréversible. Veuillez confirmer votre choix.</p>
                </div>
                <div class="modal-footer">
                    <form id="form-confirm-delete-assignment" class="form-inline" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left"></i> Annuler</button>
                        <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash"></i> Confirmer suppression</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('extra_js')
<script>
    $(document).ready(function() {

        $('.btn-delete-assignment').click(function() {
            let userId = $(this).attr('data-userid')
            let userName = $(this).attr('data-username')
            let classroomName = $(this).attr('data-classroom-name')
            let classroomId = $(this).attr('data-classroom-id')
            let assignmentId = $(this).attr('data-assignment-id')

            $('#user-name-to-delete').text(userName)
            $('#classroom-name-to-delete').text(classroomName)
            $('#form-confirm-delete-assignment').attr('action', '/assignments/' + assignmentId + '?classroom_id=' + classroomId)
        })

    });
</script>
@endsection