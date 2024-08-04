@extends('layout')

@section('title', $classroom->id ? 'Modifier une classe' : 'Créer une classe')

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
                @if ($classroom->id)
                Modifier classe
                @else
                Créer classe
                @endif
            </div>
            <div class="card-body">

                @if ($classroom->id)
                <form action="{{ Route('classrooms.update', ['period' => $period, 'classroom' => $classroom]) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('classrooms.store', ['period' => $period]) }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- short name --}}
                        <div class="row mb-3">
                            <label for="short_name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom court : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" required name="short_name" id="short_name" maxlength="10" value="{{ old('short_name', $classroom->short_name) }}">
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
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" required name="name" id="name" maxlength="50" value="{{ old('name', $classroom->name) }}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $classroom->comment) }}</textarea>
                                @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($classroom->id)
                                    Modifier classe
                                    @else
                                    Créer classe
                                    @endif
                                </button>
                                @if ($classroom->id)
                                @can('delete', [App\Models\Classroom::class, $classroom])
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteClassroom"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer classe</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$classroom" />
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

                <p>Créer / modifier une classe.</p>

                <p>Contrôles :
                <ul>
                    <li>- Le nom court de la classe est obligatoire (10 caractères max).</li>
                    <li>- Le nom court de la classe doit être unique pour son année scolaire.</li>
                    <li>- Le nom de la classe est obligatoire (50 caractères max).</li>
                    <li>- Le commentaire est facultatif , mais ne doit pas dépasser les 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($classroom->id)
<div class="modal fade" id="modalDeleteClassroom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer classe
                    <strong>{{ $classroom->short_name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à supprimer une classe. Vous supprimerez également toutes les affectations de cette classe.
                </p>

                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression de la classe <strong>{{ $classroom->name }}</strong></p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('classrooms.destroy', ['period' => $period, 'classroom' => $classroom]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', [App\Models\Classroom::class, $classroom])
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer classe <strong>{{ $classroom->short_name }}</strong></button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection