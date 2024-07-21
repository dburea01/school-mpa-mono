@extends('layout')

@section('title', $period->id ? 'Modifier une année scolaire' : 'Créer une année scolaire')

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
                @if ($period->id)
                Modifier année scolaire
                @else
                Créer année scolaire
                @endif
            </div>
            <div class="card-body">

                @if ($period->id)
                <form action="{{ Route('periods.update', $period) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('periods.store') }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- name --}}
                        <div class="row mb-3">
                            <label for="name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" required name="name" id="name" maxlength="50" value="{{ old('name', $period->name) }}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- start_date --}}
                        <div class="row mb-3">
                            <label for="start_date" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Date de début : *
                            </label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm @error('start_date') is-invalid @enderror" required name="start_date" id="start_date" value="{{ old('start_date', $period->start_date) }}">
                            </div>

                            <div class="col-sm-4">
                                <span class="form-text">(jj/mm/aaaa)</span>
                            </div>

                            @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- end_date --}}
                        <div class="row mb-3">
                            <label for="end_date" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Date de fin : *
                            </label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm @error('end_date') is-invalid @enderror" required name="end_date" id="end_date" value="{{ old('end_date', $period->end_date) }}">

                            </div>

                            <div class="col-sm-4">
                                <span class="form-text">(jj/mm/aaaa)</span>
                            </div>

                            @error('end_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- is_current --}}
                        <div class="row mb-3">
                            <label for="is_current" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Année scolaire courante ? :</label>

                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_current" id="is_current" {{ old('is_current', $period->is_current) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $period->comment) }}</textarea>
                                @error('comment'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($period->id)
                                    Modifier année scolaire
                                    @else
                                    Créer année scolaire
                                    @endif
                                </button>
                                @if ($period->id)
                                @can('delete', [App\Models\Period::class, $period])
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeletePeriod"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer année scolaire</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$period" />
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

                <p>Créez / modifiez votre période scolaire.</p>
                <p>Définissez la période scolaire courante (celle sur laquelle tous les utilisateurs travailleront une fois
                    connectés). L'année scolaire courante apparaît dans la barre de navigation (en haut à droite).</p>

                <p>Contrôles :
                <ul>
                    <li>- Le nom de la période est obligatoire (50 caractères max).</li>
                    <li>- La date de fin de période doit être aprés la date de début.</li>
                    <li>- Le commentaire est facultatif , mais ne doit pas dépasser les 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($period->id)
<div class="modal fade" id="modalDeletePeriod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer année scolaire
                    <strong>{{ $period->name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à supprimer une année scolaire. Vous supprimerez également toutes les
                    classes, affectations, notes, résultats relatifs à cette année scolaire.
                </p>

                <p>Si votre but est de ne plus rendre accessible cette année scolaire, déclarez l'année 'non courante'.
                </p>
                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression de l'année scolaire <strong>{{ $period->name }}</strong></p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('periods.destroy', $period) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', [App\Models\Period::class, $period])
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer année scolaire <strong>{{ $period->name }}</strong></button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection