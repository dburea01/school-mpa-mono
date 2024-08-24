@extends('layout')

@section('title', $assignment->id ? 'Modifier une affectation' : 'Créer une affectation')

@section('content')

<div class="row">
    <div class="col mx-auto">
        @include('errors.messages-error-info')
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-center">
                @if ($assignment->id)
                Modifier affectation dans classe <strong><span class="text-primary">{{ $classroom->short_name }}</span></strong>
                @else
                Créer affectation dans classe <strong><span class="text-primary">{{ $classroom->short_name }}</span></strong>
                @endif
            </div>
            <div class="card-body">

                @if ($assignment->id)
                <form action="{{ Route('assignments.update', ['classroom' => $classroom, 'assignment' => $assignment ]) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('assignments.store', ['classroom' => $classroom]) }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- role --}}
                        @if (! $assignment->id)
                        <div class="row mb-3">
                            <label for="role_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Rôle :
                            </label>

                            <div class="col-sm-8">
                                <x-select-role name="role_id" id="role_id" required="true" value="{{ old('role_id') }}" placeholder=" " />

                                @if ($errors->has('role_id'))
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- user to assign --}}
                        <div class="row mb-3">
                            <label for="user_name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Utilisateur : *
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control form-control-sm" id="user_name" type="text" name="user_name" value="{{ old('user_name', $user->full_name) }}"
                                    placeholder="-- Chercher une personne avec son rôle --" @if ($assignment->id) readonly disabled @endif>

                                @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <input type="text" readonly disabled id="user_id" name="user_id" value="{{ old('user_id', $user->id) }}">
                            </div>
                        </div>

                        {{-- subject --}}
                        <div class="row mb-3" id="subjects-row">
                            <label for="subject_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Matière :
                            </label>

                            <div class="col-sm-8">
                                <x-select-subject name="subject_id" id="subject_id" required="false" :value="old('subject_id', $assignment->subject_id)" placeholder=" " />

                                @error('subject_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- start_date --}}
                        <div class="row mb-3">
                            <label for="start_date" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Date début :</label>

                            <div class="col-sm-4">
                                <input type="text"
                                    class="form-control form-control-sm text-uppercase @error('start_date') is-invalid @enderror"
                                    name="start_date" id="start_date" maxlength="10"
                                    value="{{ old('start_date', $assignment->start_date) }}">
                                @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <span class="form-text">(jj/mm/aaaa)</span>
                            </div>
                        </div>

                        {{-- end_date --}}
                        <div class="row mb-3">
                            <label for="end_date" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Date fin :</label>

                            <div class="col-sm-4">
                                <input type="text"
                                    class="form-control form-control-sm text-uppercase @error('end_date') is-invalid @enderror"
                                    name="end_date" id="end_date" maxlength="10"
                                    value="{{ old('end_date', $assignment->end_date) }}">
                                @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <span class="form-text">(jj/mm/aaaa)</span>
                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $assignment->comment) }}</textarea>
                                @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($assignment->id)
                                    Modifier affectation
                                    @else
                                    Créer affectation
                                    @endif
                                </button>
                                @if ($assignment->id)
                                @can('delete', [App\Models\Assignment::class, $assignment])
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteAssignment"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer affectation</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$assignment" />
                        </div>
                    </div>
            </div>
        </div>
    </div>

    {{-- help --}}
    <div class="col-md-6 mt-3 mt-md-0">
        <div class="card shadow">
            <div class="card-header text-center"><i class="bi bi-info-circle"></i> Aide</div>
            <div class="card-body">

                <p>Créer / modifier une affectation.</p>

                <p>Contrôles :
                <ul>
                    <li>- L'utilisateur affecté est obligatoire et ne doit pas déjà être affecté dans la classe.</li>
                    <li>- La matière est facultative pour les enseignants.</li>
                    <li>- Les dates de début et de fin d'affectation sont facultatives (par défaut un utilisateur est affecté pour toute la durée de l'année scolaire.)
                    <li>- Le commentaire est facultatif , mais ne doit pas dépasser les 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($assignment->id)
<div class="modal fade" id="modalDeleteAssignment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Retirer <strong>{{ $assignment->user->full_name }}</strong> de la classe <strong>{{ $classroom->short_name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à supprimer une affectation.</p>

                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression de l'affectation de <strong>{{ $assignment->user->full_name }}</strong> de la classe <strong>{{ $assignment->classroom->short_name }}</strong></p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('assignments.destroy', ['assignment' => $assignment, 'classroom' => $classroom]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', [App\Models\Assignment::class, $assignment])
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer affectation</button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('extra_js')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    $(document).ready(function() {


        $('#role_id').on('change', function() {
            showHideSubject($('#role_id').val())
        })

        function showHideSubject(roleId) {
            if (roleId != 'STUDENT') {
                $('#subjects-row').show()
            } else {
                $('#subjects-row').hide()
            }
        }

        $("#user_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('getUsersByNameAndRole') }}",
                    data: {
                        name: request.term,
                        role_id: $('#role_id').val()
                    },
                    dataType: "json",
                    success: function(data) {
                        var resp = $.map(data.data, function(obj) {
                            console.log(obj)
                            return {
                                value: obj.first_name + ' ' + obj.last_name + ' (' + obj.role.name + ')',
                                user_id: obj.id,
                                role: obj.role
                            }
                        })
                        response(resp);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                console.log(ui)
                $('#user_id').val(ui.item.user_id)
                showHideSubject(ui.item.role.id)
            }
        });

    });
</script>
@endsection