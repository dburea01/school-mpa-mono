@extends('layout')

@section('title', $workType->id ? 'Modifier un type de travail' : 'Créer un type de travail')

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
                @if ($workType->id)
                Modifier un type de travail
                @else
                Créer un type de travail
                @endif
            </div>
            <div class="card-body">

                @if ($workType->id)
                <form action="{{ Route('work-types.update', $workType) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('work-types.store') }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- short_name --}}
                        <div class="row mb-3">
                            <label for="short_name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom court : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('short_name') is-invalid @enderror text-uppercase" required name="short_name" id="short_name" maxlength="10" value="{{ old('short_name', $workType->short_name) }}">
                                @error('short_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- name --}}
                        <div class="row mb-3">
                            <label for="name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" required name="name" id="name" maxlength="50" value="{{ old('name', $workType->name) }}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- is_active --}}
                        <div class="row mb-3">
                            <label for="is_active" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Active ? :</label>

                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $workType->is_active) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $workType->comment) }}</textarea>
                                @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($workType->id)
                                    Modifier type de travail
                                    @else
                                    Créer type de travail
                                    @endif
                                </button>
                                @if ($workType->id)
                                @can('delete', $workType)
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteWorkType"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer type de travail</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$workType" />
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

                <p>Créez / modifiez votre type de travail.</p>

                <p>Contrôles :
                <ul>
                    <li>- Le nom court est obligatoire.</li>
                    <li>- Le nom court doit être unique et ne pas dépasser les 10 caractères.</li>
                    <li>- Le nom est obligatoire.</li>
                    <li>- Le nom ne doit pas dépasser 50 caractères.</li>
                    <li>- Le commentaire est optionnel mais ne doit pas dépasser 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($workType->id)
<div class="modal fade" id="modalDeleteWorkType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer un type de travail
                    <strong>{{ $workType->name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à supprimer un type de travail. Tous les travaux existants ayant ce type de travail seront impactés.
                </p>

                <p>Si votre but est de ne plus rendre accessible ce type de travail, alors vous pouvez le déclarer inactif. Et il n'apparaîtra plus dans les listes.
                </p>
                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression du type de travail <strong>{{ $workType->name }}</strong></p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('work-types.destroy', $workType) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', $workType)
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer le type de travail <strong>{{ $workType->name }}</strong></button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection