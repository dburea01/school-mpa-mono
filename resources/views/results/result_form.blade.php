@extends('layout')

@section('title', $result->id ? 'Modifier un résultat' : 'Créer un résultat')

@section('content')

<div class="row">
    <div class="col mx-auto">
        @include('errors.messages-error-info')
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-center"><strong>{{ $work->title }}</strong>
                @if ($result->id)
                Modifier résultat
                @else
                Créer résultat
                @endif
            </div>
            <div class="card-body">

                @if ($result->id)
                <form action="{{ Route('results.update', ['work' => $work, 'result' => $result]) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('results.store', ['work' => $work]) }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- student in readonly--}}
                        <div class="row mb-3">
                            <label for="name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Elève : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" readonly disabled class="form-control form-control-sm @error('user_id') is-invalid @enderror" name="name" id="name" value="{{ $user->full_name }}">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- is_absent --}}
                        <div class="row mb-3">
                            <label for="is_absent" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Absent ? :</label>

                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_absent" id="is_absent" {{ old('is_absent', $result->is_absent) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- note --}}
                        <div class="row mb-3">
                            <label for="note" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Note : *
                            </label>

                            <div class="col-sm-4">
                                <input type="number" required min="0" max="20" step="0.1" class="form-control form-control-sm @error('note') is-invalid @enderror" name="note" id="note" value="{{ $result->note }}">
                                @error('note')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- appreciation --}}
                        <div class="row mb-3">
                            <label for="appreciation_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Appreciation :
                            </label>

                            <div class="col-sm-4">
                                <x-select-appreciation
                                    :$appreciations
                                    id="appreciation_id"
                                    name="appreciation_id"
                                    :value="old('appreciation_id', $result->appreciation_id)" />
                                @error('appreciation_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $result->comment) }}</textarea>
                                @error('comment'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($result->id)
                                    Modifier résultat
                                    @else
                                    Créer résultat
                                    @endif
                                </button>
                                @if ($result->id)
                                @can('delete', $result)
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteResult"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer résultat</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$result" />
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

                <p>Créez / modifiez un résultat.</p>
                <p>Contrôles :</p>
                <ul>
                    <li>- La note est obligatoire , sauf si l'élève est déclaré absent.</li>
                    <li>- La note doit être numérique, comprise entre 0 et 20.</li>
                    <li>- L'appréciation est facultative.</li>
                    <li>- Le commentaire est facultatif , mais ne doit pas dépasser les 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($result->id)
<div class="modal fade" id="modalDeleteResult" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer résultat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression du résultat</p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('results.destroy', ['work' => $work, 'result' => $result]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', $result)
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer résultat</button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('extra_js')
<script>
    $(document).ready(function() {

        displayHiddeElement()

        $("#is_absent").change(function() {
            displayHiddeElement();
        })

        function displayHiddeElement() {
            if ($('#is_absent').is(':checked')) {
                console.log('checked')
                $("#note").prop('readonly', true).prop('disabled', true).prop('required', false)
                $("#appreciation_id").prop('readonly', true).prop('disabled', true)
                $("#comment").prop('readonly', true).prop('disabled', true)
            } else {
                $("#note").prop('readonly', false).prop('disabled', false).prop('required', true)
                $("#appreciation_id").prop('readonly', false).prop('disabled', false)
                $("#comment").prop('readonly', false).prop('disabled', false)
            }
        }

    })
</script>
@endsection