@extends('layout')

@section('title', $work->id ? 'Modifier un travail' : 'Créer un travail')

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
                @if ($work->id)
                Modifier travail
                @else
                Créer travail
                @endif
            </div>
            <div class="card-body">

                @if ($work->id)
                <form action="{{ Route('works.update', ['work' => $work, 'period'=>$period]) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ Route('works.store', ['period'=> $period]) }}" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{-- title --}}
                        <div class="row mb-3">
                            <label for="title" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Titre : *
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" required name="title" id="title" maxlength="50" value="{{ old('title', $work->title) }}">
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- classroom --}}
                        <div class="row mb-3">
                            <label for="classroom_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Classe : *
                            </label>

                            <div class="col-sm-8">
                                @todo
                                @error('classroom_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- subject --}}
                        <div class="row mb-3">
                            <label for="subject_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Matière : *
                            </label>

                            <div class="col-sm-8">
                                @todo
                                @error('subject_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- work_type --}}
                        <div class="row mb-3">
                            <label for="work_type_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Type : *
                            </label>

                            <div class="col-sm-8">
                                <div class="mt-1">
                                    <x-select-work-type name="work_type_id" id="work_type_id" required="true" :value="old('work_type_id', $work->work_type_id)" placeholder="-" />
                                </div>
                                @error('work_type_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- work_status --}}
                        <div class="row mb-3">
                            <label for="work_status_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Status : *
                            </label>

                            <div class="col-sm-8">
                                <div class="mt-1">
                                    <x-select-work-status name="work_status_id" id="work_status_id" required="true" :value="old('work_status_id', $work->work_status_id)" placeholder="-" />
                                </div>
                                @error('work_status_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- note_min --}}
                        <div class="row mb-3">
                            <label for="note_min" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Note minimale possible :
                            </label>

                            <div class="col-sm-2">
                                <input type="number" class="form-control form-control-sm @error('note_min') is-invalid @enderror" name="note_min" id="note_min" min="0" max="20" value="{{ old('note_min', $work->note_min) }}">
                                @error('note_min')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- note_max --}}
                        <div class="row mb-3">
                            <label for="note_max" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Note maximale possible :
                            </label>

                            <div class="col-sm-2">
                                <input type="number" class="form-control form-control-sm @error('note_max') is-invalid @enderror" name="note_max" id="note_max" min="0" max="20" value="{{ old('note_max', $work->note_max) }}">
                                @error('note_max')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- note_increment --}}
                        <div class="row mb-3">
                            <label for="note_increment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Incrément de note :
                            </label>

                            <div class="col-sm-2">
                                <input type="number" class="form-control form-control-sm @error('note_increment') is-invalid @enderror" name="note_increment" id="note_increment" min="0" max="1" step="0.1" value="{{ old('note_increment', $work->note_increment) }}">
                                @error('note_increment')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- estimated_duration --}}
                        <div class="row mb-3">
                            <label for="estimated_duration" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Temps de travail estimé :
                            </label>

                            <div class="col-sm-2">
                                <input type="number" class="form-control form-control-sm @error('estimated_duration') is-invalid @enderror" name="estimated_duration" id="estimated_duration" min="0" max="180" step="15" value="{{ old('estimated_duration', $work->estimated_duration) }}">
                                @error('estimated_duration')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4">(minutes)</div>
                        </div>

                        {{-- expected_at --}}
                        <div class="row mb-3">
                            <label for="expected_at" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Date rendu travail :
                            </label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm @error('expected_at') is-invalid @enderror" name="expected_at" id="expected_at" value="{{ old('expected_at', $work->expected_at) }}">
                                @error('expected_at')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4">(jj/mm/aaaa)</div>
                        </div>

                        {{-- instruction --}}
                        <div class="row mb-3">
                            <label for="instruction" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Instruction :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('instruction') is-invalid @enderror" name="instruction" id="instruction" rows="3" maxlength="500">{{ old('instruction', $work->instruction) }}</textarea>
                                @error('instruction'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- comment --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Commentaire :</label>

                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3" maxlength="500">{{ old('comment', $work->comment) }}</textarea>
                                @error('comment'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- buttons --}}
                        <div class="row mb-3">
                            <div class="col-sm-8 offset-sm-4  d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    @if ($work->id)
                                    Modifier travail
                                    @else
                                    Créer travail
                                    @endif
                                </button>
                                @if ($work->id)
                                @can('delete', $work)
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteWork"><i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer travail</button>
                                @endcan
                                @endif
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <x-created-updated-by :model="$work" />
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

                <p>Créer / modifier un travail.</p>

                <p>Contrôles :
                <ul>
                    <li>- La classe est obligatoire.</li>
                    <li>- Le type de travail est obligatoire.</li>
                    <li>- La matière est obligatoire.</li>
                    <li>- Le status est obligatoire.</li>
                    <li>- Le titre est obligatoire et ne doit pas dépasser 50 caractères.</li>
                </ul>
                <ul>
                    <li>- Le temps estimé (en minutes) est facultatif.</li>
                    <li>- La note minimale est facultative (0 par défaut).</li>
                    <li>- La note maximale est facultative (20 par défaut).</li>
                    <li>- L'incrément pour la note est facultatif (0.5 par défaut).</li>
                    <li>- La date de retour attendu est facultative.</li>
                    <li>- Les instructions sont facultatives, et ne doivent pas dépasser les 500 caractères.</li>
                    <li>- Le commentaire est facultatif, et ne doit pas dépasser les 500 caractères.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
@if ($work->id)
<div class="modal fade" id="modalDeleteWork" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supprimer travail²
                    <strong>{{ $work->title }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à supprimer un travail. Vous supprimerez également toutes les notes relatives à ce devoir.
                </p>

                <h2 class="text-danger text-center"><strong>Action irréversible.</strong></h2>
                <p>Veuillez confirmer la suppression du travail <strong>{{ $work->title }}</strong></p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="{{ Route('works.destroy', ['work'=>$work, 'period'=>$period]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Annuler</button>
                    @can('delete', $work)
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Supprimer travail <strong>{{ $work->title }}</strong></button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection