@extends('layout')

@section('title', 'Liste des travaux')

@section('content')

@include('errors.session-values')

<div class="row">


    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header text-center">Filtres</div>
            <div class="card-body">
                <form class="row" action="{{ route('works.index', ['period' => $period]) }}" id="form-users">
                    <div class="mt-1">
                        <input type="text" class="form-control form-control-sm mr-sm-2" name="title" id="title" placeholder="Filtrer par titre/description" value="{{ $title }}">
                    </div>

                    <div class="mt-1">
                        <x-select-authorized-classroom-of-period :period="$period" :user="Auth::user()" :value="$classroomId" name="classroom_id" id="classroom_id" :required="false" placeholder="-- classe --" />
                    </div>

                    <div class="mt-1">
                        <x-select-authorized-subject name="subject_id" id="subject_id" :required="false" :period="$period" :user="Auth::user()" :value="old('subject_id', $subjectId)" placeholder="-- matière --" />
                    </div>

                    <div class="mt-1">
                        <x-select-work-type name="work_type_id" id="work_type_id" :required="false" :value="old('work_type_id', $workTypeId)" placeholder="-- type --" />
                    </div>

                    <div class="mt-1">
                        <x-select-work-status name="work_status_id" id="work_status_id" :required="false" :value="old('work_status_id', $workStatusId)" placeholder="-- status --" />
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">

        <div class="card mt-3 mt-md-0 shadow">
            <div class="card-header text-center">
                Liste des travaux ({{ $works->count() }}/{{ $works->total() }}) <strong>{{ $period->name }}</strong>
                @can('create', App\Models\Work::class)
                <a href="{{ route('works.create', ['period'=>$period]) }}">Créer travail</a>
                @endcan
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Classe</th>
                                <th>Matière</th>
                                <th>Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($works as $work)
                            <tr>
                                <td>
                                    @can('updateWork', $work)
                                    <a href="{{ route('works.edit', ['period'=>$period, 'work' => $work->id]) }}">{{ $work->title }}</a>
                                    @else
                                    {{ $work->title }}
                                    @endcan

                                    @if ($work->instruction != '')
                                    <x-tooltip-comment :comment="$work->instruction" />
                                    @endif
                                </td>
                                <td>{{ $work->work_type_short_name }}</td>
                                <td>{{ $work->classroom_short_name }}</td>
                                <td>{{ $work->subject_short_name }}</td>
                                <td>{{ $work->work_status_name }}</td>

                                <td>
                                    @can('deleteWork', $work->id)
                                    <i class="bi bi-trash btn-delete-work text-danger" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-workid="{{$work->id}}" data-worktitle="{{$work->title}}"></i>
                                    @endif

                                    @can('noteWork', $work->id)
                                    <a href="#">Corriger @todo</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="row">
                    {{ $works->withQueryString()->links() }}
                </div>

            </div>


        </div>

    </div>

    {{-- modal to delete user --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer <span id="user-name-to-delete"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>La suppression est irréversible. Veuillez confirmer votre choix.</p>
                    <p>Si votre souhait est que cet utilisateur ne puisse plus se connecter, vous pouvez également modifier son status en "BLOCKED" sans le supprimer.</p>
                </div>
                <div class="modal-footer">
                    <form id="btn-confirm-delete-user" class="form-inline" method="POST">
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

        $('.btn-delete-user').click(function() {
            let userId = $(this).attr('data-userid')
            let userName = $(this).attr('data-username')

            $('#user-name-to-delete').text(userName)
            $('#btn-confirm-delete-user').attr('action', '/users/' + userId)
        })

        $('.mode').click(function() {
            let mode = $(this).attr('data-mode')
            $('#mode').val(mode)
            $('#form-users').submit()
        })

    });
</script>
@endsection