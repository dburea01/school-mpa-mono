@extends('layout')

@section('title', $group->id ? 'Modifier un groupe' : 'Créer un groupe')

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
                @if ($group->id)
                Modifier groupe
                @else
                Créer groupe
                @endif
            </div>
            <div class="card-body">

                @if ($group->id)
                <form action="{{ Route('groups.update', $group) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('groups.store') }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- name --}}
                        <div class="row mb-3">
                            <label for="name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror text-uppercase" required name="name" id="name" maxlength="50" value="{{ old('name', $group->name) }}">
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
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $group->comment) }}</textarea>
                                @error('comment'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($group->id)
                                    Modifier groupe
                                    @else
                                    Créer groupe
                                    @endif
                                </button>
                                @if ($group->id)
                                @can('delete', [App\Models\Group::class, $group])
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteGroup"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer groupe</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$group" />
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

                <p>Créez / modifiez votre groupe.</p>
                
                <p>Contrôles :
                <ul>
                    <li>- Le nom du groupe est obligatoire (50 caractères max).</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($group->id)
<div class="modal fade" id="modalDeleteGroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer groupe
                    <strong>{{ $group->name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <p>La suppression est irréversible. Veuillez confirmer votre choix.</p>
            <p>La suppression du groupe n'entraîne pas la suppression des utilisateurs qui y appartiennent.</p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('groups.destroy', $group) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', [App\Models\Group::class, $group])
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer groupe <strong>{{ $group->name }}</strong></button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection