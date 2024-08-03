@extends('layout')

@section('title', $subject->id ? 'Modifier une matière' : 'Créer une matière')

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
                @if ($subject->id)
                Modifier matière
                @else
                Créer matière
                @endif
            </div>
            <div class="card-body">

                @if ($subject->id)
                <form action="{{ Route('subjects.update', $subject) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('subjects.store') }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- short name --}}
                        <div class="row mb-3">
                            <label for="short_name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom court : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror text-uppercase" required name="short_name" id="short_name" maxlength="10" value="{{ old('short_name', $subject->short_name) }}">
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
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror text-capitalize" required name="name" id="name" maxlength="50" value="{{ old('name', $subject->name) }}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- is_active --}}
                        <div class="row mb-3">
                            <label for="is_active" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Matière active ? :</label>

                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                                    @if ($subject->is_active == '0')
                                    <x-alert-inactive alert="Matière inactive" />
                                    @endif
                                </div>

                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $subject->comment) }}</textarea>
                                @error('comment'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($subject->id)
                                    Modifier matière
                                    @else
                                    Créer matière
                                    @endif
                                </button>
                                @if ($subject->id)
                                @can('delete', [App\Models\Subject::class, $subject])
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteSubject"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer matière</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$subject" />
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

                <p>Créez / modifiez votre matière.</p>

                <p>Contrôles :
                <ul>
                    <li>- Le nom court de la matière est obligatoire (10 caractères max).</li>
                    <li>- Le nom court de la matière doit être unique.</li>
                    <li>- Le nom de la matière est obligatoire (50 caractères max).</li>
                    <li>- Le commentaire est facultatif , mais ne doit pas dépasser les 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($subject->id)
<div class="modal fade" id="modalDeleteSubject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer matière
                    <strong>{{ $subject->name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à supprimer une matière. Vous supprimerez également tous les devoirs, tous les résultats relatifs à cette matière.
                </p>

                <p>Si votre but est de ne plus rendre accessible cette matière, rendez là inactive.</p>
                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression de la matière <strong>{{ $subject->name }}</strong></p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('subjects.destroy', $subject) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', [App\Models\Subject::class, $subject])
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer matière <strong>{{ $subject->name }}</strong></button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection